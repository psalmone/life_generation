import { useEffect, useState } from "react";
import { getPrayer, submitPrayer, markAnswered, deletePrayer } from "../api/prayer";
import { useAuth } from "../context/AuthContext";
import type { PrayerRequest } from "../types";

export default function PrayerWallPage() {
  const { user } = useAuth();
  const [requests, setRequests] = useState<PrayerRequest[]>([]);
  const [form, setForm] = useState({ title: "", body: "", visibility: "public" });
  const [msg, setMsg] = useState<{ type: "success"|"error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);

  const load = () => getPrayer().then(setRequests).finally(() => setLoading(false));
  useEffect(() => { load(); }, []);

  const flash = (type: "success"|"error", text: string) => { setMsg({ type, text }); setTimeout(() => setMsg(null), 4000); };
  const run = async (fn: () => Promise<any>, ok: string) => {
    try { await fn(); flash("success", ok); load(); }
    catch (e: any) { flash("error", e.response?.data?.error || "Something went wrong."); }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await run(() => submitPrayer(form), "Prayer request submitted. The community is praying with you! 🙏");
    setForm({ title: "", body: "", visibility: "public" });
  };

  const isAdmin = user?.role === "admin";

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>Pray Together</span>
        <h1 className="page-title">Community Prayer Wall</h1>
        <p className="page-subtitle">Lift up one another in prayer. Submit your request and let the community stand with you.</p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-2`}>{msg.text}</div>}

      <div style={{ display: "grid", gridTemplateColumns: "380px 1fr", gap: "1.5rem", alignItems: "start" }}>

        {/* ── Submit Form ── */}
        <div>
          <div className="card">
            <div style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: "1.25rem" }}>
              <div style={{
                width: 38, height: 38, borderRadius: 10,
                background: "var(--blue-soft)", border: "1px solid var(--blue-border)",
                color: "var(--blue-primary)",
                display: "flex", alignItems: "center", justifyContent: "center",
              }}>🙏</div>
              <div>
                <div className="card-title" style={{ marginBottom: 2 }}>Submit a Prayer</div>
                <div style={{ fontSize: "0.76rem", color: "var(--text-muted)" }}>Share with the community</div>
              </div>
            </div>
            <form onSubmit={handleSubmit} style={{ display: "flex", flexDirection: "column", gap: "0.9rem" }}>
              <div className="form-group">
                <label className="form-label">Title *</label>
                <input
                  placeholder="Brief title for your request"
                  value={form.title}
                  onChange={e => setForm(p => ({ ...p, title: e.target.value }))}
                  required maxLength={200}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Prayer Request *</label>
                <textarea
                  rows={5}
                  placeholder="Share what's on your heart…"
                  value={form.body}
                  onChange={e => setForm(p => ({ ...p, body: e.target.value }))}
                  required
                  style={{ resize: "vertical" }}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Visibility</label>
                <select
                  value={form.visibility}
                  onChange={e => setForm(p => ({ ...p, visibility: e.target.value }))}
                >
                  <option value="public">🌐 Public — visible to all members</option>
                  <option value="private">🔒 Private — visible only to admins</option>
                </select>
              </div>
              <button className="btn btn-primary" type="submit" style={{ width: "100%" }}>
                Submit Prayer Request
              </button>
            </form>
          </div>

          {/* Scripture */}
          <div style={{
            marginTop: "1rem", padding: "1rem 1.25rem",
            background: "var(--blue-soft)", border: "1px solid var(--blue-border)",
            borderLeft: "4px solid var(--blue-primary)",
            borderRadius: "var(--radius-md)",
          }}>
            <p style={{ fontSize: "0.84rem", fontStyle: "italic", color: "var(--text-secondary)", lineHeight: 1.6 }}>
              "Do not be anxious about anything, but in everything by prayer and supplication with thanksgiving let your requests be made known to God."
            </p>
            <p style={{ fontSize: "0.76rem", fontWeight: 700, color: "var(--blue-primary)", marginTop: 6, textAlign: "right" }}>— Philippians 4:6</p>
          </div>
        </div>

        {/* ── Prayer Requests List ── */}
        <div>
          {loading && (
            <div style={{ textAlign: "center", padding: "3rem", color: "var(--text-muted)" }}>Loading prayer requests…</div>
          )}
          {!loading && requests.length === 0 && (
            <div style={{ textAlign: "center", padding: "3rem" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: 12 }}>🙏</div>
              <p style={{ color: "var(--text-muted)", fontWeight: 600 }}>No prayer requests yet.</p>
              <p style={{ color: "var(--text-light)", fontSize: "0.85rem", marginTop: 4 }}>Be the first to share a request with the community.</p>
            </div>
          )}
          <div style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
            {requests.map(r => (
              <div className="prayer-card" key={r.id}>
                {/* Header */}
                <div>
                  <div className="flex-between mb-2">
                    <div className="flex-center gap-1">
                      <div className="prayer-avatar">
                        {(r.member_name ?? "A").charAt(0).toUpperCase()}
                      </div>
                      <div>
                        <div style={{ fontSize: "0.88rem", fontWeight: 700, color: "var(--text-main)" }}>
                          {r.member_name}
                        </div>
                        <div style={{ fontSize: "0.72rem", color: "var(--text-light)" }}>
                          {new Date(r.created_at).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })}
                        </div>
                      </div>
                    </div>
                    <div style={{ display: "flex", alignItems: "center", gap: 6 }}>
                      {r.visibility === "private" && (
                        <span style={{ fontSize: "0.7rem", color: "var(--text-muted)", background: "var(--bg-soft)", padding: "2px 7px", borderRadius: 6, border: "1px solid var(--card-border)", fontWeight: 700 }}>
                          🔒 Private
                        </span>
                      )}
                      <span className={`badge ${r.status === "answered" ? "badge-success" : "badge-blue"}`}>
                        {r.status === "answered" ? "✓ Answered" : "🙏 Praying"}
                      </span>
                    </div>
                  </div>

                  <div style={{ fontSize: "0.98rem", fontWeight: 800, marginBottom: "6px", color: "var(--text-main)" }}>
                    {r.title}
                  </div>
                  <p style={{ fontSize: "0.85rem", color: "var(--text-muted)", lineHeight: 1.6 }}>{r.body}</p>
                </div>

                {/* Actions */}
                {(r.user_id === user?.id || isAdmin) && (
                  <div className="flex-center gap-1 mt-2" style={{ flexWrap: "wrap" }}>
                    {r.status !== "answered" && (
                      <button
                        className="btn btn-success btn-sm"
                        onClick={() => run(() => markAnswered(r.id), "Praise God! Marked as answered. 🎉")}
                      >
                        ✓ Mark Answered
                      </button>
                    )}
                    <button
                      className="btn btn-danger btn-sm"
                      onClick={() => run(() => deletePrayer(r.id), "Prayer request removed.")}
                    >
                      Remove
                    </button>
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
