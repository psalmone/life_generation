import { useEffect, useState } from "react";
import { getDGroups, joinDGroup, leaveDGroup } from "../api/dgroups";
import { getDashboard } from "../api/dashboard";
import type { DGroup } from "../types";

export default function DGroupsPage() {
  const [dgroups, setDgroups] = useState<DGroup[]>([]);
  const [myDgroupId, setMyDgroupId] = useState<number | null>(null);
  const [msg, setMsg] = useState<{ type: "success"|"error"; text: string } | null>(null);

  const load = async () => {
    const [list, dash] = await Promise.all([getDGroups(), getDashboard()]);
    setDgroups(list);
    setMyDgroupId(dash.member?.dgroup_id ?? null);
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
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>Grow Together</span>
        <h1 className="page-title">Discipleship Groups</h1>
        <p className="page-subtitle">Connect with a small group and grow in faith, community, and purpose.</p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-2`}>{msg.text}</div>}
      {myDgroupId && (
        <div className="alert alert-info mb-3">
          📌 You are currently in a D-Group. Leave it first to join another.
        </div>
      )}

      <div className="grid-3">
        {dgroups.map(g => {
          const isMine = g.id === myDgroupId;
          return (
            <div className="card" key={g.id} style={isMine ? { borderColor: "var(--orange-primary)", boxShadow: "0 0 0 2px rgba(249,115,22,0.12)" } : {}}>
              {g.image && (
                <img src={`/${g.image}`} className="event-img" alt={g.name} />
              )}
              <div style={{ display: "flex", alignItems: "flex-start", justifyContent: "space-between", marginBottom: "8px" }}>
                <div>
                  {isMine && <span className="badge badge-accent mb-1">Your Group ✓</span>}
                </div>
              </div>
              <div className="card-title">{g.name}</div>
              <div style={{ display: "flex", flexDirection: "column", gap: 5, marginBottom: "12px" }}>
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
                <p style={{ color: "var(--text-light)", fontSize: "0.8rem" }}>👥 {g.member_count ?? 0} members</p>
              </div>
              {g.description && (
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55, marginBottom: "14px" }}>{g.description}</p>
              )}
              <div style={{ marginTop: "auto" }}>
                {isMine ? (
                  <button className="btn btn-danger btn-sm" onClick={() => run(leaveDGroup, "You left the D-Group.")}>
                    Leave Group
                  </button>
                ) : (
                  <button className="btn btn-primary btn-sm" onClick={() => run(() => joinDGroup(g.id), `Joined ${g.name}!`)}>
                    Join This Group
                  </button>
                )}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
