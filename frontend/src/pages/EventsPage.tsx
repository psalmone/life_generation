import { useEffect, useState } from "react";
import { getEvents, rsvpEvent, cancelRsvp } from "../api/events";
import { getDashboard } from "../api/dashboard";
import type { Event } from "../types";

export default function EventsPage() {
  const [events, setEvents] = useState<Event[]>([]);
  const [rsvpMap, setRsvpMap] = useState<Record<number, any>>({});
  const [msg, setMsg] = useState<{ type: "success"|"error"; text: string } | null>(null);

  const load = async () => {
    const [evts, dash] = await Promise.all([getEvents(), getDashboard()]);
    setEvents(evts);
    setRsvpMap(Object.fromEntries((dash.user_rsvps || []).map((r: any) => [r.event_id, r])));
  };
  useEffect(() => { load(); }, []);

  const flash = (type: "success"|"error", text: string) => { setMsg({ type, text }); setTimeout(() => setMsg(null), 4000); };
  const run = async (fn: () => Promise<any>, ok: string) => {
    try { await fn(); flash("success", ok); load(); }
    catch (e: any) { flash("error", e.response?.data?.error || "Something went wrong."); }
  };

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>Gather With Us</span>
        <h1 className="page-title">Upcoming Church Events</h1>
        <p className="page-subtitle">Join our Sunday services, youth fellowships, and special conferences.</p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-2`}>{msg.text}</div>}

      <div className="grid-3">
        {events.map(e => {
          const myRsvp = rsvpMap[e.id];
          const isAttending = myRsvp?.rsvp_status === "attending";
          const isCheckedIn = myRsvp?.checked_in === 1;
          return (
            <div className="card" key={e.id} style={isAttending ? { borderColor: "var(--blue-primary)", boxShadow: "0 0 0 2px rgba(59,130,246,0.12)" } : {}}>
              {e.banner_image && (
                <img src={`/${e.banner_image}`} className="event-img" alt={e.title} />
              )}

              <div className="card-tag">
                <svg width="11" height="11" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {new Date(e.event_date).toLocaleDateString("en-PH", { weekday: "short", month: "long", day: "numeric" })}
              </div>

              <div className="card-title">{e.title}</div>

              <div style={{ display: "flex", flexDirection: "column", gap: 5, marginBottom: 10 }}>
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
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55, marginBottom: 12 }}>{e.description}</p>
              )}

              <div style={{ display: "flex", alignItems: "center", gap: 8, marginTop: "auto", flexWrap: "wrap" }}>
                {isCheckedIn ? (
                  <span className="badge badge-success">✓ Checked In</span>
                ) : isAttending ? (
                  <>
                    <span className="badge badge-blue">✓ RSVP'd</span>
                    <button className="btn btn-danger btn-sm" onClick={() => run(() => cancelRsvp(e.id), "RSVP cancelled.")}>
                      Cancel RSVP
                    </button>
                  </>
                ) : (
                  <button className="btn btn-primary btn-sm" onClick={() => run(() => rsvpEvent(e.id), `RSVP confirmed for ${e.title}!`)}>
                    RSVP Now
                  </button>
                )}
              </div>
            </div>
          );
        })}
        {events.length === 0 && (
          <p style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "3rem" }}>
            No upcoming events right now. Check back soon! 🙏
          </p>
        )}
      </div>
    </div>
  );
}
