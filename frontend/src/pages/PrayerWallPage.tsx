import { useEffect, useState } from "react";
import { getPrayer, submitPrayer, markAnswered, deletePrayer } from "../api/prayer";
import { useAuth } from "../context/AuthContext";
import type { PrayerRequest } from "../types";

export default function PrayerWallPage() {
  const { user } = useAuth();
  const [requests, setRequests] = useState<PrayerRequest[]>([]);
  const [form, setForm] = useState({ title: "", body: "", visibility: "public" });
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<"all" | "answered" | "my">("all");
  const [search, setSearch] = useState("");

  const load = () =>
    getPrayer()
      .then(setRequests)
      .finally(() => setLoading(false));

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

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await run(() => submitPrayer(form), "Prayer request submitted. The community is praying with you! 🙏");
    setForm({ title: "", body: "", visibility: "public" });
  };

  const isAdmin = user?.role === "admin";

  const filteredRequests = requests.filter((r) => {
    if (filter === "answered" && r.status !== "answered") return false;
    if (filter === "my" && r.user_id !== user?.id) return false;
    if (search.trim()) {
      const q = search.toLowerCase();
      return (
        r.title.toLowerCase().includes(q) ||
        r.body.toLowerCase().includes(q) ||
        (r.member_name && r.member_name.toLowerCase().includes(q))
      );
    }
    return true;
  });

  return (
    <div className="container page">
      <div className="page-header">
        <span className="section-tag" style={{ display: "block", marginBottom: 6 }}>
          Pray Together
        </span>
        <h1 className="page-title">Community Prayer Wall</h1>
        <p className="page-subtitle">
          Lift up one another in prayer. Submit your request and let the church family stand with you in agreement.
        </p>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-3`}>{msg.text}</div>}

      <div style={{ display: "grid", gridTemplateColumns: "360px 1fr", gap: "1.5rem", alignItems: "start" }} className="prayer-wall-layout">
        {/* ── Submit Form ── */}
        <div>
          <div className="card">
            <div style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: "1.25rem" }}>
              <div
                style={{
                  width: 38,
                  height: 38,
                  borderRadius: 10,
                  background: "var(--blue-soft)",
                  border: "1px solid var(--blue-border)",
                  color: "var(--blue-primary)",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                  fontSize: "1.2rem",
                }}
              >
                🙏
              </div>
              <div>
                <div className="card-title" style={{ marginBottom: 2 }}>
                  Submit a Prayer
                </div>
                <div style={{ fontSize: "0.76rem", color: "var(--text-muted)" }}>
                  Share with the fellowship
                </div>
              </div>
            </div>

            <form onSubmit={handleSubmit} style={{ display: "flex", flexDirection: "column", gap: "0.9rem" }}>
              <div className="form-group">
                <label className="form-label">Title *</label>
                <input
                  placeholder="e.g. Healing for family, Guidance in studies…"
                  value={form.title}
                  onChange={(e) => setForm((p) => ({ ...p, title: e.target.value }))}
                  required
                  maxLength={200}
                />
              </div>

              <div className="form-group">
                <label className="form-label">Prayer Request *</label>
                <textarea
                  rows={4}
                  placeholder="Share what is on your heart…"
                  value={form.body}
                  onChange={(e) => setForm((p) => ({ ...p, body: e.target.value }))}
                  required
                  style={{ resize: "vertical" }}
                />
              </div>

              <div className="form-group">
                <label className="form-label">Visibility</label>
                <select
                  value={form.visibility}
                  onChange={(e) => setForm((p) => ({ ...p, visibility: e.target.value }))}
                >
                  <option value="public">🌐 Public — visible to all church members</option>
                  <option value="private">🔒 Private — visible only to pastors & pastoral team</option>
                </select>
              </div>

              <button className="btn btn-primary" type="submit" style={{ width: "100%" }}>
                Submit Prayer Request
              </button>
            </form>
          </div>

          {/* Scripture Encouragement */}
          <div
            style={{
              marginTop: "1rem",
              padding: "1.1rem 1.25rem",
              background: "var(--blue-soft)",
              border: "1px solid var(--blue-border)",
              borderLeft: "4px solid var(--blue-primary)",
              borderRadius: "var(--radius-md)",
            }}
          >
            <p style={{ fontSize: "0.85rem", fontStyle: "italic", color: "var(--text-secondary)", lineHeight: 1.6 }}>
              "Do not be anxious about anything, but in everything by prayer and supplication with thanksgiving let your requests be made known to God."
            </p>
            <p style={{ fontSize: "0.76rem", fontWeight: 700, color: "var(--blue-primary)", marginTop: 6, textAlign: "right" }}>
              — Philippians 4:6
            </p>
          </div>
        </div>

        {/* ── Prayer Requests List ── */}
        <div>
          {/* Filter & Search Header */}
          <div className="search-filter-bar">
            <div className="filter-pills">
              <button
                className={`filter-pill ${filter === "all" ? "active" : ""}`}
                onClick={() => setFilter("all")}
              >
                All Prayers ({requests.length})
              </button>
              <button
                className={`filter-pill ${filter === "answered" ? "active" : ""}`}
                onClick={() => setFilter("answered")}
              >
                Praises & Answered ({requests.filter((r) => r.status === "answered").length})
              </button>
              <button
                className={`filter-pill ${filter === "my" ? "active" : ""}`}
                onClick={() => setFilter("my")}
              >
                My Prayers ({requests.filter((r) => r.user_id === user?.id).length})
              </button>
            </div>

            <div className="search-box">
              <svg className="search-box-icon" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                type="text"
                placeholder="Search prayer requests…"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
          </div>

          {loading && (
            <div style={{ textAlign: "center", padding: "3rem", color: "var(--text-muted)" }}>
              Loading prayer requests…
            </div>
          )}

          {!loading && filteredRequests.length === 0 && (
            <div style={{ textAlign: "center", padding: "3rem", background: "var(--bg-surface)", border: "1px solid var(--card-border)", borderRadius: "var(--radius-lg)" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: 10 }}>🙏</div>
              <p style={{ color: "var(--text-muted)", fontWeight: 700 }}>No prayer requests found.</p>
              <p style={{ color: "var(--text-light)", fontSize: "0.85rem", marginTop: 4 }}>
                {search ? "Try adjusting your search keywords." : "Be the first to share a request with the fellowship."}
              </p>
            </div>
          )}

          <div style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
            {filteredRequests.map((r) => (
              <div className="prayer-card" key={r.id}>
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
                          {new Date(r.created_at).toLocaleDateString("en-PH", {
                            month: "short",
                            day: "numeric",
                            year: "numeric",
                          })}
                        </div>
                      </div>
                    </div>
                    <div style={{ display: "flex", alignItems: "center", gap: 6 }}>
                      {r.visibility === "private" && (
                        <span
                          style={{
                            fontSize: "0.7rem",
                            color: "var(--text-muted)",
                            background: "var(--bg-soft)",
                            padding: "2px 7px",
                            borderRadius: 6,
                            border: "1px solid var(--card-border)",
                            fontWeight: 700,
                          }}
                        >
                          🔒 Private
                        </span>
                      )}
                      <span className={`badge ${r.status === "answered" ? "badge-success" : "badge-blue"}`}>
                        {r.status === "answered" ? "✓ Answered Praise" : "🙏 Praying"}
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
                  <div className="flex-center gap-1 mt-2" style={{ flexWrap: "wrap", borderTop: "1px solid var(--card-border-subtle)", paddingTop: 8 }}>
                    {r.status !== "answered" && (
                      <button
                        className="btn btn-success btn-sm"
                        style={{ padding: "3px 10px", fontSize: "0.75rem" }}
                        onClick={() => run(() => markAnswered(r.id), "Praise God! Marked as answered praise. 🎉")}
                      >
                        ✓ Mark Answered Praise
                      </button>
                    )}
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ padding: "3px 10px", fontSize: "0.75rem", color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                      onClick={() => {
                        if (confirm("Remove this prayer request?")) {
                          run(() => deletePrayer(r.id), "Prayer request removed.");
                        }
                      }}
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
