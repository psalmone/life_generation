import { useEffect, useState } from "react";
import { getMinistries, applyMinistry, cancelMinistry } from "../api/ministries";
import type { Ministry } from "../types";

export default function MinistriesPage() {
  const [ministries, setMinistries] = useState<Ministry[]>([]);
  const [msg, setMsg] = useState<{ type: "success"|"error"; text: string } | null>(null);

  const load = () => getMinistries().then(setMinistries).catch(() => {});
  useEffect(() => { load(); }, []);

  const flash = (type: "success"|"error", text: string) => { setMsg({ type, text }); setTimeout(() => setMsg(null), 4000); };
  const run = async (fn: () => Promise<any>, ok: string) => {
    try { await fn(); flash("success", ok); load(); }
    catch (e: any) { flash("error", e.response?.data?.error || "Something went wrong."); }
  };

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>Serve & Grow</span>
        <h1 className="page-title">Ministry Teams</h1>
        <p className="page-subtitle">Discover your calling and serve alongside passionate believers.</p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-2`}>{msg.text}</div>}

      <div className="grid-3">
        {ministries.map(m => (
          <div className="card" key={m.id}
            style={m.application_status === "approved" ? { borderColor: "var(--orange-primary)", boxShadow: "0 0 0 2px rgba(249,115,22,0.1)" } : {}}
          >
            {m.image && (
              <img src={`/${m.image}`} className="event-img" alt={m.name} />
            )}

            {m.icon && (
              <div style={{ fontSize: "2.2rem", marginBottom: "10px", lineHeight: 1 }}>{m.icon}</div>
            )}

            <div className="card-title">{m.name}</div>

            {m.description && (
              <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.6, marginBottom: "1rem" }}>
                {m.description}
              </p>
            )}

            <div style={{ marginTop: "auto" }}>
              {m.application_status === "approved" ? (
                <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                  <span className="badge badge-success">✓ Active Member</span>
                  <button
                    className="btn btn-outline btn-sm"
                    onClick={() => run(() => cancelMinistry(m.id), "Left the ministry team.")}
                  >
                    Leave
                  </button>
                </div>
              ) : m.application_status === "pending" ? (
                <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                  <span className="badge badge-accent">Pending Review</span>
                  <button
                    className="btn btn-outline btn-sm"
                    onClick={() => run(() => cancelMinistry(m.id), "Application withdrawn.")}
                  >
                    Withdraw
                  </button>
                </div>
              ) : m.application_status === "rejected" ? (
                <span className="badge badge-danger">Application Declined</span>
              ) : (
                <button
                  className="btn btn-primary btn-sm"
                  onClick={() => run(() => applyMinistry(m.id, ""), `Applied to ${m.name}!`)}
                >
                  Apply to Volunteer
                </button>
              )}
            </div>
          </div>
        ))}

        {ministries.length === 0 && (
          <p style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "3rem" }}>
            No active ministry teams at the moment.
          </p>
        )}
      </div>
    </div>
  );
}
