import { useEffect, useState } from "react";
import { getDGroups, joinDGroup, leaveDGroup } from "../api/dgroups";
import { getDashboard } from "../api/dashboard";
import type { DGroup } from "../types";

export default function DGroupsPage() {
  const [dgroups, setDgroups] = useState<DGroup[]>([]);
  const [myDgroup, setMyDgroup] = useState<DGroup | null>(null);
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");

  const load = async () => {
    try {
      const [list, dash] = await Promise.all([getDGroups(), getDashboard()]);
      setDgroups(list);
      setMyDgroup(dash.current_dgroup ?? null);
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

  const filteredGroups = dgroups.filter((g) => {
    if (!search.trim()) return true;
    const q = search.toLowerCase();
    return (
      g.name.toLowerCase().includes(q) ||
      g.leader_name.toLowerCase().includes(q) ||
      g.location.toLowerCase().includes(q) ||
      g.schedule.toLowerCase().includes(q)
    );
  });

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>
          Grow Together
        </span>
        <h1 className="page-title">Discipleship Groups (D-Groups)</h1>
        <p className="page-subtitle">
          Small circles where believers encourage one another, study God's Word, and do life together.
        </p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-3`}>{msg.text}</div>}

      {/* ── Prominent Current D-Group Banner if Enrolled ── */}
      {myDgroup && (
        <div
          className="card mb-3"
          style={{
            borderColor: "var(--orange-primary)",
            background: "linear-gradient(135deg, rgba(249,115,22,0.06) 0%, rgba(56,189,248,0.04) 100%)",
            boxShadow: "0 0 0 1px rgba(249,115,22,0.2)",
          }}
        >
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start", gap: 16, flexWrap: "wrap" }}>
            <div>
              <span className="badge badge-accent mb-2">Your Current D-Group ✓</span>
              <h2 style={{ fontSize: "1.3rem", fontWeight: 800, marginBottom: 6 }}>
                {myDgroup.name}
              </h2>
              <div style={{ display: "flex", flexWrap: "wrap", gap: 14, color: "var(--text-muted)", fontSize: "0.86rem", marginBottom: 10 }}>
                <span>👤 <strong>Leader:</strong> {myDgroup.leader_name}</span>
                <span>🕐 <strong>Schedule:</strong> {myDgroup.schedule}</span>
                <span>📍 <strong>Location:</strong> {myDgroup.location}</span>
                <span>👥 <strong>Members:</strong> {myDgroup.member_count ?? 0}</span>
              </div>
              {myDgroup.description && (
                <p style={{ fontSize: "0.84rem", color: "var(--text-secondary)", lineHeight: 1.5, maxWidth: 640 }}>
                  {myDgroup.description}
                </p>
              )}
            </div>
            <button
              className="btn btn-outline btn-sm"
              style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
              onClick={() => {
                if (confirm(`Leave ${myDgroup.name}?`)) {
                  run(leaveDGroup, "You left the D-Group.");
                }
              }}
            >
              Leave Group
            </button>
          </div>
        </div>
      )}

      {/* ── Search Bar ── */}
      <div className="search-filter-bar">
        <div style={{ fontSize: "0.92rem", fontWeight: 700, color: "var(--text-main)" }}>
          All Available D-Groups ({dgroups.length})
        </div>
        <div className="search-box">
          <svg className="search-box-icon" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            type="text"
            placeholder="Search by group name, leader, schedule, or venue…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      {loading ? (
        <div style={{ textAlign: "center", padding: "4rem", color: "var(--text-muted)" }}>
          Loading D-Groups…
        </div>
      ) : (
        <div className="grid-3">
          {filteredGroups.map((g) => {
            const isMine = g.id === myDgroup?.id;
            return (
              <div
                className="card"
                key={g.id}
                style={{
                  display: "flex",
                  flexDirection: "column",
                  ...(isMine
                    ? { borderColor: "var(--orange-primary)", boxShadow: "0 0 0 2px rgba(249,115,22,0.14)" }
                    : {}),
                }}
              >
                {g.image && <img src={`/${g.image}`} className="event-img" alt={g.name} />}

                <div style={{ display: "flex", alignItems: "flex-start", justifyContent: "space-between", marginBottom: 6 }}>
                  {isMine ? (
                    <span className="badge badge-accent">Your Group ✓</span>
                  ) : (
                    <span className="card-tag">D-Group</span>
                  )}
                </div>

                <div className="card-title">{g.name}</div>

                <div style={{ display: "flex", flexDirection: "column", gap: 6, marginBottom: 12 }}>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", display: "flex", alignItems: "center", gap: 6 }}>
                    <svg width="13" height="13" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: "var(--orange-primary)", flexShrink: 0 }}>
                      <path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {g.leader_name}
                  </p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", display: "flex", alignItems: "center", gap: 6 }}>
                    <svg width="13" height="13" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: "var(--orange-primary)", flexShrink: 0 }}>
                      <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {g.schedule}
                  </p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", display: "flex", alignItems: "center", gap: 6 }}>
                    <svg width="13" height="13" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: "var(--orange-primary)", flexShrink: 0 }}>
                      <path strokeLinecap="round" strokeLinejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path strokeLinecap="round" strokeLinejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {g.location}
                  </p>
                  <p style={{ color: "var(--text-light)", fontSize: "0.8rem" }}>
                    👥 {g.member_count ?? 0} members
                  </p>
                </div>

                {g.description && (
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55, marginBottom: 14 }}>
                    {g.description}
                  </p>
                )}

                <div style={{ marginTop: "auto" }}>
                  {isMine ? (
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                      onClick={() => run(leaveDGroup, "You left the D-Group.")}
                    >
                      Leave Group
                    </button>
                  ) : (
                    <button
                      className="btn btn-primary btn-sm"
                      onClick={() => run(() => joinDGroup(g.id), `Joined ${g.name}!`)}
                    >
                      Join This Group
                    </button>
                  )}
                </div>
              </div>
            );
          })}

          {filteredGroups.length === 0 && (
            <div style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "3rem" }}>
              <div style={{ fontSize: "2rem", marginBottom: 8 }}>👥</div>
              <p style={{ fontWeight: 600 }}>No D-Groups found.</p>
              <p style={{ fontSize: "0.85rem", color: "var(--text-light)", marginTop: 4 }}>
                {search ? "Try adjusting your search query." : "No active groups at the moment."}
              </p>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
