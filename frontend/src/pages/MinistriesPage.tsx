import { useEffect, useState } from "react";
import { getMinistries, applyMinistry, cancelMinistry } from "../api/ministries";
import { getDashboard } from "../api/dashboard";
import type { Ministry } from "../types";

export default function MinistriesPage() {
  const [ministries, setMinistries] = useState<Ministry[]>([]);
  const [currentMinistry, setCurrentMinistry] = useState<Ministry | null>(null);
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [applyModal, setApplyModal] = useState<Ministry | null>(null);
  const [applyMessage, setApplyMessage] = useState("");

  const load = async () => {
    try {
      const [list, dash] = await Promise.all([getMinistries(), getDashboard()]);
      setMinistries(list);
      setCurrentMinistry(dash.current_ministry ?? null);
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

  const submitApplication = async () => {
    if (!applyModal) return;
    await run(
      () => applyMinistry(applyModal.id, applyMessage),
      `Application submitted for ${applyModal.name}! The ministry lead will review it soon.`
    );
    setApplyModal(null);
    setApplyMessage("");
  };

  const filteredMinistries = ministries.filter((m) => {
    if (!search.trim()) return true;
    const q = search.toLowerCase();
    return (
      m.name.toLowerCase().includes(q) ||
      (m.description && m.description.toLowerCase().includes(q))
    );
  });

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>
          Serve & Grow
        </span>
        <h1 className="page-title">Church Ministry Teams</h1>
        <p className="page-subtitle">
          Discover your God-given spiritual gifts and serve alongside passionate believers in music, production, hospitality, and outreach.
        </p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-3`}>{msg.text}</div>}

      {/* ── Active Ministry Highlight ── */}
      {currentMinistry && (
        <div
          className="card mb-3"
          style={{
            borderColor: "var(--blue-primary)",
            background: "linear-gradient(135deg, rgba(56,189,248,0.06) 0%, rgba(249,115,22,0.04) 100%)",
            boxShadow: "0 0 0 1px rgba(56,189,248,0.2)",
          }}
        >
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", gap: 16, flexWrap: "wrap" }}>
            <div style={{ display: "flex", alignItems: "center", gap: 14 }}>
              <span style={{ fontSize: "2.4rem" }}>{currentMinistry.icon || "✦"}</span>
              <div>
                <span className="badge badge-success mb-1">Your Active Ministry ✓</span>
                <h2 style={{ fontSize: "1.25rem", fontWeight: 800 }}>{currentMinistry.name}</h2>
                <p style={{ color: "var(--text-muted)", fontSize: "0.85rem", marginTop: 2 }}>
                  Thank you for faithfully serving in our church community!
                </p>
              </div>
            </div>
            <button
              className="btn btn-outline btn-sm"
              style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
              onClick={() => {
                if (confirm(`Leave ${currentMinistry.name}?`)) {
                  run(() => cancelMinistry(currentMinistry.id), "You left the ministry team.");
                }
              }}
            >
              Leave Team
            </button>
          </div>
        </div>
      )}

      {/* ── Search Bar ── */}
      <div className="search-filter-bar">
        <div style={{ fontSize: "0.92rem", fontWeight: 700, color: "var(--text-main)" }}>
          Available Ministries ({ministries.length})
        </div>
        <div className="search-box">
          <svg className="search-box-icon" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            type="text"
            placeholder="Search ministry by name or description…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      {loading ? (
        <div style={{ textAlign: "center", padding: "4rem", color: "var(--text-muted)" }}>
          Loading ministry teams…
        </div>
      ) : (
        <div className="grid-3">
          {filteredMinistries.map((m) => {
            const isApproved = m.application_status === "approved";
            const isPending = m.application_status === "pending";
            const isRejected = m.application_status === "rejected";

            return (
              <div
                className="card"
                key={m.id}
                style={{
                  display: "flex",
                  flexDirection: "column",
                  ...(isApproved
                    ? { borderColor: "var(--orange-primary)", boxShadow: "0 0 0 2px rgba(249,115,22,0.12)" }
                    : {}),
                }}
              >
                {m.image && <img src={`/${m.image}`} className="event-img" alt={m.name} />}

                {m.icon && (
                  <div style={{ fontSize: "2.2rem", marginBottom: 8, lineHeight: 1 }}>{m.icon}</div>
                )}

                <div className="card-title">{m.name}</div>

                {m.description && (
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.6, marginBottom: "1.2rem" }}>
                    {m.description}
                  </p>
                )}

                <div style={{ marginTop: "auto" }}>
                  {isApproved ? (
                    <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                      <span className="badge badge-success">✓ Active Member</span>
                      <button
                        className="btn btn-outline btn-sm"
                        style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                        onClick={() => run(() => cancelMinistry(m.id), "Left the ministry team.")}
                      >
                        Leave
                      </button>
                    </div>
                  ) : isPending ? (
                    <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                      <span className="badge badge-accent">Pending Review</span>
                      <button
                        className="btn btn-outline btn-sm"
                        onClick={() => run(() => cancelMinistry(m.id), "Application withdrawn.")}
                      >
                        Withdraw
                      </button>
                    </div>
                  ) : isRejected ? (
                    <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                      <span className="badge badge-danger">Declined</span>
                      <button
                        className="btn btn-outline btn-sm"
                        onClick={() => {
                          setApplyModal(m);
                          setApplyMessage("");
                        }}
                      >
                        Re-apply
                      </button>
                    </div>
                  ) : (
                    <button
                      className="btn btn-primary btn-sm"
                      onClick={() => {
                        setApplyModal(m);
                        setApplyMessage("");
                      }}
                    >
                      Apply to Volunteer
                    </button>
                  )}
                </div>
              </div>
            );
          })}

          {filteredMinistries.length === 0 && (
            <div style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "3rem" }}>
              <div style={{ fontSize: "2rem", marginBottom: 8 }}>✦</div>
              <p style={{ fontWeight: 600 }}>No ministries found.</p>
              <p style={{ fontSize: "0.85rem", color: "var(--text-light)", marginTop: 4 }}>
                {search ? "Try adjusting your search query." : "No active ministry teams at the moment."}
              </p>
            </div>
          )}
        </div>
      )}

      {/* ── Volunteer Application Modal ── */}
      {applyModal && (
        <div className="modal-overlay" onClick={(e) => e.target === e.currentTarget && setApplyModal(null)}>
          <div className="modal">
            <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "1.25rem" }}>
              <h2 style={{ fontSize: "1.15rem", fontWeight: 800 }}>
                Volunteer for {applyModal.name}
              </h2>
              <button
                onClick={() => setApplyModal(null)}
                style={{ background: "none", border: "none", fontSize: "1.5rem", color: "var(--text-muted)", lineHeight: 1, cursor: "pointer" }}
              >
                ×
              </button>
            </div>

            <p style={{ fontSize: "0.86rem", color: "var(--text-muted)", marginBottom: "1rem", lineHeight: 1.5 }}>
              Share why you feel called to serve in this ministry, your availability, or any relevant experience you would like the team lead to know.
            </p>

            <div className="form-group mb-3">
              <label className="form-label">Message / Notes (Optional)</label>
              <textarea
                rows={3}
                placeholder="e.g. I play guitar and would love to join Sunday worship rehearsals…"
                value={applyMessage}
                onChange={(e) => setApplyMessage(e.target.value)}
                style={{ resize: "vertical" }}
              />
            </div>

            <div style={{ display: "flex", justifyContent: "flex-end", gap: 8 }}>
              <button className="btn btn-outline btn-sm" onClick={() => setApplyModal(null)}>
                Cancel
              </button>
              <button className="btn btn-primary btn-sm" onClick={submitApplication}>
                Submit Volunteer Application
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
