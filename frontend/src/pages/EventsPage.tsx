import { useEffect, useState } from "react";
import { getEvents, rsvpEvent, cancelRsvp } from "../api/events";
import { getDashboard } from "../api/dashboard";
import type { Event } from "../types";

export default function EventsPage() {
  const [events, setEvents] = useState<Event[]>([]);
  const [rsvpMap, setRsvpMap] = useState<Record<number, any>>({});
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState<"all" | "attending">("all");

  const load = async () => {
    try {
      const [evts, dash] = await Promise.all([getEvents(), getDashboard()]);
      setEvents(evts);
      setRsvpMap(Object.fromEntries((dash.user_rsvps || []).map((r: any) => [r.event_id, r])));
    } catch {
      // ignore
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, []);

  const flash = (type: "success" | "error", text: string) => {
    setMsg({ type, text });
    setTimeout(() => setMsg(null), 4000);
  };

  const run = async (fn: () => Promise<any>, ok: string) => {
    try {
      await fn();
      flash("success", ok);
      load();
    } catch (e: any) {
      flash("error", e.response?.data?.error || "Something went wrong.");
    }
  };

  const filteredEvents = events.filter((e) => {
    const isAttending = rsvpMap[e.id]?.rsvp_status === "attending";
    if (filter === "attending" && !isAttending) return false;
    if (search.trim()) {
      const q = search.toLowerCase();
      return (
        e.title.toLowerCase().includes(q) ||
        e.location.toLowerCase().includes(q) ||
        (e.description && e.description.toLowerCase().includes(q))
      );
    }
    return true;
  });

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>
          Gather With Us
        </span>
        <h1 className="page-title">Church Events & Gatherings</h1>
        <p className="page-subtitle">
          Join our worship celebrations, youth nights, discipleship conferences, and community outreaches.
        </p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-3`}>{msg.text}</div>}

      {/* ── Search & Filter Controls ── */}
      <div className="search-filter-bar">
        <div className="filter-pills">
          <button
            className={`filter-pill ${filter === "all" ? "active" : ""}`}
            onClick={() => setFilter("all")}
          >
            All Events ({events.length})
          </button>
          <button
            className={`filter-pill ${filter === "attending" ? "active" : ""}`}
            onClick={() => setFilter("attending")}
          >
            My Registrations ({Object.values(rsvpMap).filter((r: any) => r.rsvp_status === "attending").length})
          </button>
        </div>

        <div className="search-box">
          <svg className="search-box-icon" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            type="text"
            placeholder="Search events by title or venue…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      {loading ? (
        <div style={{ textAlign: "center", padding: "4rem", color: "var(--text-muted)" }}>
          Loading upcoming events…
        </div>
      ) : (
        <div className="grid-3">
          {filteredEvents.map((e) => {
            const myRsvp = rsvpMap[e.id];
            const isAttending = myRsvp?.rsvp_status === "attending";
            const isCheckedIn = myRsvp?.checked_in === 1;

            return (
              <div
                className="card"
                key={e.id}
                style={{
                  display: "flex",
                  flexDirection: "column",
                  ...(isAttending
                    ? { borderColor: "var(--blue-primary)", boxShadow: "0 0 0 2px rgba(59,130,246,0.14)" }
                    : {}),
                }}
              >
                {e.banner_image && (
                  <img src={`/${e.banner_image}`} className="event-img" alt={e.title} />
                )}

                <div className="card-tag">
                  <svg width="11" height="11" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {new Date(e.event_date).toLocaleDateString("en-PH", {
                    weekday: "short",
                    month: "long",
                    day: "numeric",
                  })}
                </div>

                <div className="card-title">{e.title}</div>

                <div style={{ display: "flex", flexDirection: "column", gap: 6, marginBottom: 12 }}>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", display: "flex", alignItems: "center", gap: 6 }}>
                    <svg width="13" height="13" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: "var(--orange-primary)", flexShrink: 0 }}>
                      <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {e.event_time}
                  </p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", display: "flex", alignItems: "center", gap: 6 }}>
                    <svg width="13" height="13" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: "var(--orange-primary)", flexShrink: 0 }}>
                      <path strokeLinecap="round" strokeLinejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path strokeLinecap="round" strokeLinejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {e.location}
                  </p>
                  {e.capacity && (
                    <p style={{ color: "var(--text-light)", fontSize: "0.8rem" }}>
                      👥 {e.rsvp_count ?? 0}/{e.capacity} attending
                    </p>
                  )}
                </div>

                {e.description && (
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55, marginBottom: 14 }}>
                    {e.description}
                  </p>
                )}

                <div style={{ display: "flex", alignItems: "center", gap: 8, marginTop: "auto", flexWrap: "wrap" }}>
                  {isCheckedIn ? (
                    <span className="badge badge-success">✓ Checked In</span>
                  ) : isAttending ? (
                    <>
                      <span className="badge badge-blue">✓ Registered</span>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                        onClick={() => run(() => cancelRsvp(e.id), "RSVP cancelled.")}
                      >
                        Cancel RSVP
                      </button>
                    </>
                  ) : (
                    <button
                      className="btn btn-primary btn-sm"
                      onClick={() => run(() => rsvpEvent(e.id), `RSVP confirmed for ${e.title}!`)}
                    >
                      RSVP Now
                    </button>
                  )}
                </div>
              </div>
            );
          })}

          {filteredEvents.length === 0 && (
            <div style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "3rem" }}>
              <div style={{ fontSize: "2rem", marginBottom: 8 }}>🗓</div>
              <p style={{ fontWeight: 600 }}>No events found.</p>
              <p style={{ fontSize: "0.85rem", color: "var(--text-light)", marginTop: 4 }}>
                {search ? "Try clearing your search query." : "Check back soon for new gathering announcements!"}
              </p>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
