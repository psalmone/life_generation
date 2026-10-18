import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import {
  getDashboard,
  updateProfile,
  uploadAvatar,
  removeAvatar,
  toggleMemberStatus,
  updateMemberRole,
} from "../api/dashboard";
import { cancelRsvp, createEvent, deleteEvent } from "../api/events";
import { leaveDGroup, createDGroup, deleteDGroup } from "../api/dgroups";
import {
  cancelMinistry,
  updateApplication,
  createMinistry,
  deleteMinistry,
} from "../api/ministries";
import { markAnswered, deletePrayer } from "../api/prayer";
import type { DashboardData } from "../types";

/* ── Avatar Helper ── */
function Avatar({
  src,
  name,
  size = 48,
}: {
  src?: string | null;
  name: string;
  size?: number;
}) {
  const style: React.CSSProperties = {
    width: size,
    height: size,
    borderRadius: "50%",
    objectFit: "cover",
    background: "var(--orange-soft)",
    border: "2px solid var(--orange-border)",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    fontSize: size * 0.38,
    fontWeight: 800,
    color: "var(--orange-primary)",
    flexShrink: 0,
    overflow: "hidden",
  };
  if (src) {
    return <img src={`/${src}`} style={{ ...style, display: "block" }} alt={name} />;
  }
  return <div style={style}>{name?.[0]?.toUpperCase() ?? "?"}</div>;
}

/* ── Modal Component ── */
function Modal({
  title,
  onClose,
  children,
}: {
  title: string;
  onClose: () => void;
  children: React.ReactNode;
}) {
  return (
    <div className="modal-overlay" onClick={(e) => e.target === e.currentTarget && onClose()}>
      <div className="modal">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "1.25rem" }}>
          <h2 style={{ fontSize: "1.15rem", fontWeight: 800, letterSpacing: "-0.01em" }}>{title}</h2>
          <button
            onClick={onClose}
            style={{ background: "none", border: "none", fontSize: "1.5rem", color: "var(--text-muted)", lineHeight: 1, cursor: "pointer" }}
          >
            ×
          </button>
        </div>
        {children}
      </div>
    </div>
  );
}

/* ── Main Dashboard Page ── */
export default function DashboardPage() {
  const [data, setData] = useState<DashboardData | null>(null);
  const [tab, setTab] = useState<string>("overview");
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);
  const [copied, setCopied] = useState(false);
  const [memberSearch, setMemberSearch] = useState("");

  const load = () =>
    getDashboard()
      .then(setData)
      .catch(() => setMsg({ type: "error", text: "Failed to load dashboard." }))
      .finally(() => setLoading(false));

  useEffect(() => {
    load();
  }, []);

  const flash = (type: "success" | "error", text: string) => {
    setMsg({ type, text });
    setTimeout(() => setMsg(null), 4500);
  };

  const run = async (fn: () => Promise<any>, successMsg: string) => {
    try {
      await fn();
      flash("success", successMsg);
      load();
    } catch (e: any) {
      flash("error", e.response?.data?.error || "Something went wrong.");
    }
  };

  const copyMemberId = (id: string) => {
    navigator.clipboard.writeText(id);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  if (loading) {
    return (
      <div style={{ minHeight: "65vh", display: "flex", alignItems: "center", justifyContent: "center" }}>
        <div style={{ textAlign: "center" }}>
          <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>✦</div>
          <p style={{ color: "var(--text-muted)", fontWeight: 600 }}>Loading Member Portal…</p>
        </div>
      </div>
    );
  }

  if (!data) {
    return (
      <div className="container page">
        <div className="alert alert-error">Failed to load dashboard. Please refresh the page.</div>
      </div>
    );
  }

  const {
    user,
    member,
    current_dgroup,
    current_ministry,
    user_apps,
    upcoming_events,
    user_rsvps,
    prayer_requests,
  } = data;

  const isAdmin = user.role === "admin";
  const rsvpMap = Object.fromEntries((user_rsvps || []).map((r: any) => [r.event_id, r]));

  // Events the user has registered for
  const myEvents = (upcoming_events || []).filter((e) => rsvpMap[e.id]?.rsvp_status === "attending");

  // User's own prayer requests
  const myPrayers = (prayer_requests || []).filter((p) => p.user_id === user.id);

  // Tabs list: Clean, focused, non-redundant!
  const tabs = [
    { id: "overview", label: "My Church Life" },
    { id: "profile", label: "Profile & Settings" },
    ...(isAdmin
      ? [
          { id: "members", label: "Member Directory" },
          { id: "admin-events", label: "Manage Events" },
          { id: "admin-dgroups", label: "Manage D-Groups" },
          { id: "admin-ministries", label: "Manage Ministries" },
          { id: "admin-apps", label: "Applications" },
        ]
      : []),
  ];

  return (
    <div className="container page">
      {/* ── Member Portal Hero Banner ── */}
      <div className="dashboard-header mb-3">
        <div style={{ display: "flex", alignItems: "center", gap: "1.25rem", position: "relative", zIndex: 1, flexWrap: "wrap" }}>
          <Avatar src={member.avatar} name={member.full_name} size={68} />
          <div>
            <div style={{ fontSize: "0.76rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.08em", color: "rgba(255,255,255,0.7)", marginBottom: 4 }}>
              Member Portal · Life Generation
            </div>
            <h1 style={{ fontSize: "1.6rem", fontWeight: 800, letterSpacing: "-0.02em", color: "#fff", lineHeight: 1.2 }}>
              {member.full_name}
            </h1>
            <div style={{ display: "flex", alignItems: "center", gap: 8, marginTop: 8, flexWrap: "wrap" }}>
              <button
                type="button"
                onClick={() => copyMemberId(member.member_id)}
                className="member-id-pill"
                title="Click to copy Member ID"
                style={{ background: "rgba(249, 115, 22, 0.25)", border: "1px solid rgba(249, 115, 22, 0.5)", cursor: "pointer" }}
              >
                <span>ID: {member.member_id}</span>
                <span style={{ fontSize: "0.7rem", opacity: 0.85 }}>{copied ? "✓ Copied" : "📋"}</span>
              </button>
              {isAdmin && (
                <span style={{ fontSize: "0.72rem", fontWeight: 800, padding: "3px 10px", background: "rgba(255,255,255,0.22)", borderRadius: 9999, color: "#fff" }}>
                  ADMIN
                </span>
              )}
              {current_dgroup && (
                <span style={{ fontSize: "0.72rem", fontWeight: 700, padding: "3px 10px", background: "rgba(255,255,255,0.15)", borderRadius: 9999, color: "rgba(255,255,255,0.95)" }}>
                  📍 {current_dgroup.name}
                </span>
              )}
              {current_ministry && (
                <span style={{ fontSize: "0.72rem", fontWeight: 700, padding: "3px 10px", background: "rgba(56,189,248,0.2)", borderRadius: 9999, color: "#e0f2fe" }}>
                  ✦ {current_ministry.name}
                </span>
              )}
            </div>
          </div>
        </div>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-3`}>{msg.text}</div>}

      {/* ── Navigation Tabs ── */}
      <div className="tabs mb-3" style={{ overflowX: "auto" }}>
        {tabs.map((t) => (
          <button
            key={t.id}
            className={`tab-btn ${tab === t.id ? "active" : ""}`}
            onClick={() => setTab(t.id)}
          >
            {t.label}
          </button>
        ))}
      </div>

      {/* ══════════════════════════════════════════════════════
          TAB 1: MY CHURCH LIFE (Personal Overview & Involvement)
          ══════════════════════════════════════════════════════ */}
      {tab === "overview" && (
        <div className="grid-2">
          {/* 1. My Discipleship Group */}
          <div className="card hub-card">
            <div>
              <div className="hub-card-header">
                <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                  <div className="hub-icon-wrap hub-icon-orange">👥</div>
                  <div>
                    <div style={{ fontSize: "0.75rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: "var(--text-muted)" }}>
                      Discipleship
                    </div>
                    <div className="card-title" style={{ marginBottom: 0 }}>My D-Group</div>
                  </div>
                </div>
                {current_dgroup && <span className="badge badge-accent">Enrolled ✓</span>}
              </div>

              {current_dgroup ? (
                <div>
                  <h3 style={{ fontSize: "1.15rem", fontWeight: 800, marginBottom: 8, color: "var(--text-main)" }}>
                    {current_dgroup.name}
                  </h3>
                  <div style={{ display: "flex", flexDirection: "column", gap: 6, fontSize: "0.85rem", color: "var(--text-muted)", marginBottom: "1rem" }}>
                    <p>👤 <strong>Leader:</strong> {current_dgroup.leader_name}</p>
                    <p>🕐 <strong>Schedule:</strong> {current_dgroup.schedule}</p>
                    <p>📍 <strong>Location:</strong> {current_dgroup.location}</p>
                    <p>👥 <strong>Members:</strong> {current_dgroup.member_count ?? 0} participants</p>
                  </div>
                  {current_dgroup.description && (
                    <p style={{ fontSize: "0.83rem", color: "var(--text-secondary)", lineHeight: 1.5, marginBottom: "1rem" }}>
                      {current_dgroup.description}
                    </p>
                  )}
                  <div style={{ display: "flex", gap: 8, flexWrap: "wrap", marginTop: "auto" }}>
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                      onClick={() => {
                        if (confirm(`Are you sure you want to leave ${current_dgroup.name}?`)) {
                          run(leaveDGroup, "You left the D-Group.");
                        }
                      }}
                    >
                      Leave Group
                    </button>
                    <Link to="/dgroups" className="btn btn-outline btn-sm">
                      Browse Other Groups →
                    </Link>
                  </div>
                </div>
              ) : (
                <div className="hub-empty-state">
                  <div className="hub-empty-icon">🤝</div>
                  <h4 style={{ fontWeight: 800, fontSize: "0.98rem" }}>No Active D-Group</h4>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", maxWidth: 300, lineHeight: 1.5 }}>
                    Discipleship groups are small community circles where faith grows through sharing, prayer, and brotherhood.
                  </p>
                  <Link to="/dgroups" className="btn btn-primary btn-sm mt-1">
                    Find & Join a D-Group →
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* 2. My Ministry & Volunteer Team */}
          <div className="card hub-card">
            <div>
              <div className="hub-card-header">
                <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                  <div className="hub-icon-wrap hub-icon-blue">✦</div>
                  <div>
                    <div style={{ fontSize: "0.75rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: "var(--text-muted)" }}>
                      Service & Calling
                    </div>
                    <div className="card-title" style={{ marginBottom: 0 }}>My Ministry Team</div>
                  </div>
                </div>
                {current_ministry && <span className="badge badge-success">Active Volunteer ✓</span>}
              </div>

              {current_ministry ? (
                <div>
                  <div style={{ display: "flex", alignItems: "center", gap: 8, marginBottom: 8 }}>
                    <span style={{ fontSize: "1.6rem" }}>{current_ministry.icon || "✦"}</span>
                    <h3 style={{ fontSize: "1.15rem", fontWeight: 800, color: "var(--text-main)" }}>
                      {current_ministry.name}
                    </h3>
                  </div>
                  {current_ministry.description && (
                    <p style={{ fontSize: "0.84rem", color: "var(--text-muted)", lineHeight: 1.55, marginBottom: "1.2rem" }}>
                      {current_ministry.description}
                    </p>
                  )}
                  <div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
                    <Link to="/ministries" className="btn btn-outline btn-sm">
                      Explore All Ministry Teams →
                    </Link>
                  </div>
                </div>
              ) : user_apps && user_apps.length > 0 ? (
                <div>
                  <p style={{ fontSize: "0.85rem", color: "var(--text-muted)", marginBottom: 10 }}>
                    You have submitted volunteer applications:
                  </p>
                  <div style={{ display: "flex", flexDirection: "column", gap: 8, marginBottom: "1rem" }}>
                    {user_apps.map((app) => (
                      <div
                        key={app.id}
                        style={{
                          display: "flex",
                          justifyContent: "space-between",
                          alignItems: "center",
                          padding: "10px 12px",
                          borderRadius: "var(--radius-sm)",
                          background: "var(--bg-soft)",
                          border: "1px solid var(--card-border)",
                        }}
                      >
                        <div>
                          <div style={{ fontWeight: 700, fontSize: "0.9rem" }}>
                            {app.icon || "✦"} {app.ministry_name}
                          </div>
                          {app.message && (
                            <div style={{ fontSize: "0.78rem", color: "var(--text-muted)", marginTop: 2 }}>
                              Note: {app.message}
                            </div>
                          )}
                        </div>
                        <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                          <span
                            className={`badge badge-${
                              app.status === "approved"
                                ? "success"
                                : app.status === "rejected"
                                ? "danger"
                                : "accent"
                            }`}
                          >
                            {app.status === "pending" ? "Pending Review" : app.status}
                          </span>
                          {app.status === "pending" && (
                            <button
                              className="btn btn-outline btn-sm"
                              style={{ padding: "2px 8px", fontSize: "0.72rem" }}
                              onClick={() => run(() => cancelMinistry(app.ministry_id), "Application withdrawn.")}
                            >
                              Withdraw
                            </button>
                          )}
                        </div>
                      </div>
                    ))}
                  </div>
                  <Link to="/ministries" className="btn btn-outline btn-sm">
                    View Ministry Directory →
                  </Link>
                </div>
              ) : (
                <div className="hub-empty-state">
                  <div className="hub-empty-icon">✝</div>
                  <h4 style={{ fontWeight: 800, fontSize: "0.98rem" }}>Not Serving Yet</h4>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", maxWidth: 300, lineHeight: 1.5 }}>
                    Discover your spiritual gifts in worship, media, hospitality, and outreach. Every member has a vital role.
                  </p>
                  <Link to="/ministries" className="btn btn-primary btn-sm mt-1">
                    Explore Ministry Teams →
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* 3. My Upcoming RSVPs */}
          <div className="card hub-card">
            <div>
              <div className="hub-card-header">
                <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                  <div className="hub-icon-wrap hub-icon-orange">📅</div>
                  <div>
                    <div style={{ fontSize: "0.75rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: "var(--text-muted)" }}>
                      Calendar & Gatherings
                    </div>
                    <div className="card-title" style={{ marginBottom: 0 }}>My Event RSVPs</div>
                  </div>
                </div>
                {myEvents.length > 0 && <span className="badge badge-blue">{myEvents.length} Registered</span>}
              </div>

              {myEvents.length > 0 ? (
                <div style={{ display: "flex", flexDirection: "column", gap: 10 }}>
                  {myEvents.map((evt) => {
                    const rsvp = rsvpMap[evt.id];
                    const isCheckedIn = rsvp?.checked_in === 1;
                    return (
                      <div
                        key={evt.id}
                        style={{
                          padding: "12px",
                          borderRadius: "var(--radius-md)",
                          background: "var(--bg-soft)",
                          border: "1px solid var(--card-border)",
                          display: "flex",
                          justifyContent: "space-between",
                          alignItems: "center",
                          gap: 12,
                          flexWrap: "wrap",
                        }}
                      >
                        <div>
                          <div style={{ fontWeight: 800, fontSize: "0.92rem", color: "var(--text-main)" }}>
                            {evt.title}
                          </div>
                          <div style={{ fontSize: "0.8rem", color: "var(--text-muted)", marginTop: 3 }}>
                            🗓 {new Date(evt.event_date).toLocaleDateString("en-PH", { weekday: "short", month: "short", day: "numeric" })} · {evt.event_time}
                          </div>
                          <div style={{ fontSize: "0.78rem", color: "var(--text-light)", marginTop: 2 }}>
                            📍 {evt.location}
                          </div>
                        </div>
                        <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                          {isCheckedIn ? (
                            <span className="badge badge-success">✓ Checked In</span>
                          ) : (
                            <>
                              <span className="badge badge-blue">✓ Attending</span>
                              <button
                                className="btn btn-outline btn-sm"
                                style={{ padding: "4px 8px", fontSize: "0.74rem" }}
                                onClick={() => run(() => cancelRsvp(evt.id), "RSVP cancelled.")}
                              >
                                Cancel
                              </button>
                            </>
                          )}
                        </div>
                      </div>
                    );
                  })}
                  <div style={{ marginTop: 8 }}>
                    <Link to="/events" className="btn btn-outline btn-sm">
                      Discover More Events →
                    </Link>
                  </div>
                </div>
              ) : (
                <div className="hub-empty-state">
                  <div className="hub-empty-icon">🎟</div>
                  <h4 style={{ fontWeight: 800, fontSize: "0.98rem" }}>No Event RSVPs</h4>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", maxWidth: 300, lineHeight: 1.5 }}>
                    You have not registered for any upcoming gatherings. Join our Sunday celebration services and special conferences!
                  </p>
                  <Link to="/events" className="btn btn-primary btn-sm mt-1">
                    Browse Church Events →
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* 4. My Prayer Requests */}
          <div className="card hub-card">
            <div>
              <div className="hub-card-header">
                <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                  <div className="hub-icon-wrap hub-icon-blue">🙏</div>
                  <div>
                    <div style={{ fontSize: "0.75rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: "var(--text-muted)" }}>
                      Intercession
                    </div>
                    <div className="card-title" style={{ marginBottom: 0 }}>My Prayer Requests</div>
                  </div>
                </div>
                {myPrayers.length > 0 && <span className="badge badge-accent">{myPrayers.length} Requests</span>}
              </div>

              {myPrayers.length > 0 ? (
                <div style={{ display: "flex", flexDirection: "column", gap: 10 }}>
                  {myPrayers.map((req) => (
                    <div
                      key={req.id}
                      style={{
                        padding: "12px",
                        borderRadius: "var(--radius-md)",
                        background: "var(--bg-soft)",
                        border: "1px solid var(--card-border)",
                      }}
                    >
                      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start", gap: 8, marginBottom: 4 }}>
                        <div style={{ fontWeight: 700, fontSize: "0.9rem", color: "var(--text-main)" }}>
                          {req.title}
                        </div>
                        <span className={`badge ${req.status === "answered" ? "badge-success" : "badge-blue"}`}>
                          {req.status === "answered" ? "✓ Answered" : "🙏 Praying"}
                        </span>
                      </div>
                      <p style={{ fontSize: "0.82rem", color: "var(--text-muted)", lineHeight: 1.5, marginBottom: 8 }}>
                        {req.body}
                      </p>
                      <div style={{ display: "flex", alignItems: "center", gap: 8, flexWrap: "wrap" }}>
                        {req.status !== "answered" && (
                          <button
                            className="btn btn-success btn-sm"
                            style={{ padding: "3px 8px", fontSize: "0.74rem" }}
                            onClick={() => run(() => markAnswered(req.id), "Praise God! Marked answered. 🎉")}
                          >
                            ✓ Mark Answered
                          </button>
                        )}
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ padding: "3px 8px", fontSize: "0.74rem", color: "var(--danger)" }}
                          onClick={() => run(() => deletePrayer(req.id), "Prayer removed.")}
                        >
                          Remove
                        </button>
                      </div>
                    </div>
                  ))}
                  <div style={{ marginTop: 8 }}>
                    <Link to="/prayer-wall" className="btn btn-outline btn-sm">
                      Visit Prayer Wall →
                    </Link>
                  </div>
                </div>
              ) : (
                <div className="hub-empty-state">
                  <div className="hub-empty-icon">🕊</div>
                  <h4 style={{ fontWeight: 800, fontSize: "0.98rem" }}>No Prayer Requests</h4>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", maxWidth: 300, lineHeight: 1.5 }}>
                    Let our church family stand with you in faith. Submit a request and our community will lift you up in prayer.
                  </p>
                  <Link to="/prayer-wall" className="btn btn-primary btn-sm mt-1">
                    Submit a Prayer Request →
                  </Link>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          TAB 2: PROFILE & SETTINGS (Unified, Non-Redundant!)
          ══════════════════════════════════════════════════════ */}
      {tab === "profile" && (
        <div className="profile-card-unified">
          {/* Hero Profile Summary with Avatar Upload */}
          <div className="profile-hero-banner">
            <div style={{ display: "flex", alignItems: "center", gap: "1.25rem", flexWrap: "wrap" }}>
              <div className="profile-avatar-container">
                <Avatar src={member.avatar} name={member.full_name} size={88} />
              </div>
              <div>
                <div style={{ display: "flex", alignItems: "center", gap: 8, flexWrap: "wrap", marginBottom: 4 }}>
                  <h2 style={{ fontSize: "1.35rem", fontWeight: 800, letterSpacing: "-0.01em" }}>
                    {member.full_name}
                  </h2>
                  <span className="member-id-pill">ID: {member.member_id}</span>
                  <span className={`badge badge-${member.is_active ? "success" : "danger"}`}>
                    {member.is_active ? "Active Member" : "Inactive"}
                  </span>
                </div>
                <div style={{ fontSize: "0.86rem", color: "var(--text-muted)", marginBottom: 8 }}>
                  ✉️ {member.email} · 🗓 Member since{" "}
                  {member.joined_at
                    ? new Date(member.joined_at).toLocaleDateString("en-PH", {
                        month: "long",
                        day: "numeric",
                        year: "numeric",
                      })
                    : "Community Member"}
                </div>
                <div style={{ display: "flex", gap: 8, flexWrap: "wrap", alignItems: "center" }}>
                  <label className="btn btn-outline btn-sm" style={{ cursor: "pointer" }}>
                    📷 Change Photo
                    <input
                      type="file"
                      accept="image/*"
                      style={{ display: "none" }}
                      onChange={async (e) => {
                        if (e.target.files?.[0]) {
                          await run(() => uploadAvatar(e.target.files![0]), "Photo updated!");
                        }
                      }}
                    />
                  </label>
                  {member.avatar && (
                    <button
                      className="btn btn-outline btn-sm"
                      style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                      onClick={() => run(removeAvatar, "Photo removed.")}
                    >
                      Remove Photo
                    </button>
                  )}
                </div>
              </div>
            </div>

            {/* Quick Affiliations */}
            <div style={{ display: "flex", flexDirection: "column", gap: 6, minWidth: 200 }}>
              <div style={{ fontSize: "0.78rem", fontWeight: 700, color: "var(--text-muted)", textTransform: "uppercase", letterSpacing: "0.05em" }}>
                Current Affiliations
              </div>
              <div style={{ fontSize: "0.88rem", fontWeight: 700 }}>
                D-Group: <span style={{ color: "var(--orange-primary)" }}>{current_dgroup?.name ?? "None"}</span>
              </div>
              <div style={{ fontSize: "0.88rem", fontWeight: 700 }}>
                Ministry: <span style={{ color: "var(--blue-primary)" }}>{current_ministry?.name ?? "None"}</span>
              </div>
            </div>
          </div>

          {/* Unified Profile Edit Form */}
          <div className="profile-form-body">
            <h3 style={{ fontSize: "1.05rem", fontWeight: 800, marginBottom: "0.5rem" }}>
              Edit Personal Details
            </h3>
            <p style={{ color: "var(--text-muted)", fontSize: "0.85rem", marginBottom: "1.5rem" }}>
              Keep your contact and church membership information up to date.
            </p>

            <ProfileEditForm
              member={member}
              onSave={async (d) => {
                await run(() => updateProfile(d), "Profile updated successfully!");
              }}
            />
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          ADMIN TAB: MEMBER DIRECTORY
          ══════════════════════════════════════════════════════ */}
      {tab === "members" && isAdmin && (
        <div>
          <div className="search-filter-bar">
            <div>
              <span className="section-tag">Church Administration</span>
              <h2 style={{ fontSize: "1.3rem", fontWeight: 800 }}>
                Member Directory ({data.total_members ?? 0})
              </h2>
            </div>
            <div className="search-box">
              <svg className="search-box-icon" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                type="text"
                placeholder="Search member by name, email, or ID…"
                value={memberSearch}
                onChange={(e) => setMemberSearch(e.target.value)}
              />
            </div>
          </div>

          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table>
                <thead>
                  <tr>
                    {["Member", "Member ID", "Role", "Ministry", "D-Group", "Status", "Action"].map((h) => (
                      <th key={h}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_members || [])
                    .filter((m) => {
                      if (!memberSearch) return true;
                      const q = memberSearch.toLowerCase();
                      return (
                        m.full_name?.toLowerCase().includes(q) ||
                        m.email?.toLowerCase().includes(q) ||
                        m.member_id?.toLowerCase().includes(q)
                      );
                    })
                    .map((m) => (
                      <tr key={m.id}>
                        <td>
                          <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                            <Avatar src={m.avatar} name={m.full_name} size={34} />
                            <div>
                              <div style={{ fontWeight: 700 }}>{m.full_name}</div>
                              <div style={{ color: "var(--text-muted)", fontSize: "0.78rem" }}>{m.email}</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span className="member-id-pill">{m.member_id}</span>
                        </td>
                        <td>
                          <select
                            style={{ width: "auto", fontSize: "0.8rem", padding: "4px 8px" }}
                            defaultValue={m.role}
                            onChange={(e) => run(() => updateMemberRole(m.user_id!, e.target.value), "Role updated.")}
                          >
                            {["user", "moderator", "admin"].map((r) => (
                              <option key={r} value={r}>
                                {r}
                              </option>
                            ))}
                          </select>
                        </td>
                        <td style={{ color: "var(--text-muted)" }}>{m.ministry_name || "—"}</td>
                        <td style={{ color: "var(--text-muted)" }}>{m.dgroup_name || "—"}</td>
                        <td>
                          <span className={`badge badge-${m.is_active ? "success" : "danger"}`}>
                            {m.is_active ? "Active" : "Inactive"}
                          </span>
                        </td>
                        <td>
                          <button
                            className="btn btn-outline btn-sm"
                            onClick={() => run(() => toggleMemberStatus(m.id!), "Status updated.")}
                          >
                            {m.is_active ? "Deactivate" : "Activate"}
                          </button>
                        </td>
                      </tr>
                    ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          ADMIN TAB: MANAGE EVENTS
          ══════════════════════════════════════════════════════ */}
      {tab === "admin-events" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <div>
              <span className="section-tag">Calendar Management</span>
              <h2 style={{ fontSize: "1.3rem", fontWeight: 800 }}>Manage Events</h2>
            </div>
            <CreateEventForm onSave={async (fd) => { await run(() => createEvent(fd), "Event created!"); }} />
          </div>
          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table>
                <thead>
                  <tr>
                    {["Event", "Date & Time", "Location", "RSVPs", "Action"].map((h) => (
                      <th key={h}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_events_admin || []).map((e) => (
                    <tr key={e.id}>
                      <td style={{ fontWeight: 700 }}>{e.title}</td>
                      <td style={{ color: "var(--text-muted)" }}>
                        {new Date(e.event_date).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })} · {e.event_time}
                      </td>
                      <td style={{ color: "var(--text-muted)" }}>{e.location}</td>
                      <td>
                        <span className="badge badge-blue">{e.rsvp_count ?? 0} attendees</span>
                      </td>
                      <td>
                        <button
                          className="btn btn-outline btn-sm"
                          style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                          onClick={() => {
                            if (confirm("Delete this event?")) run(() => deleteEvent(e.id), "Event deleted.");
                          }}
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  ))}
                  {(data.all_events_admin || []).length === 0 && (
                    <tr>
                      <td colSpan={5} style={{ textAlign: "center", color: "var(--text-muted)", padding: "2rem" }}>
                        No events created yet.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          ADMIN TAB: MANAGE D-GROUPS
          ══════════════════════════════════════════════════════ */}
      {tab === "admin-dgroups" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <div>
              <span className="section-tag">Small Groups Administration</span>
              <h2 style={{ fontSize: "1.3rem", fontWeight: 800 }}>Manage D-Groups</h2>
            </div>
            <CreateDGroupForm onSave={async (fd) => { await run(() => createDGroup(fd), "D-Group created!"); }} />
          </div>
          <div className="grid-3">
            {(data.all_dgroups_admin || []).map((g) => (
              <div className="card" key={g.id}>
                <div className="card-title">{g.name}</div>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>👤 {g.leader_name}</p>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>🕐 {g.schedule}</p>
                <p style={{ color: "var(--text-light)", fontSize: "0.8rem", marginBottom: 12 }}>👥 {g.member_count ?? 0} members</p>
                <button
                  className="btn btn-outline btn-sm"
                  style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                  onClick={() => {
                    if (confirm("Delete this D-Group?")) run(() => deleteDGroup(g.id), "D-Group deleted.");
                  }}
                >
                  Delete D-Group
                </button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          ADMIN TAB: MANAGE MINISTRIES
          ══════════════════════════════════════════════════════ */}
      {tab === "admin-ministries" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <div>
              <span className="section-tag">Volunteer Ministry Teams</span>
              <h2 style={{ fontSize: "1.3rem", fontWeight: 800 }}>Manage Ministries</h2>
            </div>
            <CreateMinistryForm onSave={async (fd) => { await run(() => createMinistry(fd), "Ministry created!"); }} />
          </div>
          <div className="grid-3">
            {(data.all_ministries_admin || []).map((m) => (
              <div className="card" key={m.id}>
                {m.icon && <div style={{ fontSize: "1.8rem", marginBottom: 8 }}>{m.icon}</div>}
                <div className="card-title">{m.name}</div>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 12 }}>
                  👥 {m.member_count ?? 0} members · 📋 {m.pending_apps_count ?? 0} pending
                </p>
                <button
                  className="btn btn-outline btn-sm"
                  style={{ color: "var(--danger)", borderColor: "rgba(244,63,94,0.3)" }}
                  onClick={() => {
                    if (confirm("Delete this ministry?")) run(() => deleteMinistry(m.id), "Ministry deleted.");
                  }}
                >
                  Delete Ministry
                </button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ══════════════════════════════════════════════════════
          ADMIN TAB: APPLICATIONS
          ══════════════════════════════════════════════════════ */}
      {tab === "admin-apps" && isAdmin && (
        <div>
          <div className="mb-3">
            <span className="section-tag">Volunteer Review</span>
            <h2 style={{ fontSize: "1.3rem", fontWeight: 800 }}>Ministry Volunteer Applications</h2>
          </div>
          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table>
                <thead>
                  <tr>
                    {["Applicant", "Ministry", "Message / Note", "Status", "Actions"].map((h) => (
                      <th key={h}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_ministry_apps || []).map((a) => (
                    <tr key={a.id}>
                      <td>
                        <div style={{ fontWeight: 700 }}>{a.member_name}</div>
                        <div style={{ color: "var(--text-muted)", fontSize: "0.78rem" }}>{a.member_id}</div>
                      </td>
                      <td>
                        <span style={{ fontWeight: 600 }}>{a.icon} {a.ministry_name}</span>
                      </td>
                      <td style={{ color: "var(--text-muted)", maxWidth: 240 }}>{a.message || "—"}</td>
                      <td>
                        <span className={`badge badge-${a.status === "approved" ? "success" : a.status === "rejected" ? "danger" : "accent"}`}>
                          {a.status}
                        </span>
                      </td>
                      <td>
                        <div style={{ display: "flex", gap: 6 }}>
                          <button
                            className="btn btn-success btn-sm"
                            onClick={() => run(() => updateApplication(a.id, "approved"), "Approved! 🎉")}
                          >
                            Approve
                          </button>
                          <button
                            className="btn btn-outline btn-sm"
                            style={{ color: "var(--danger)" }}
                            onClick={() => run(() => updateApplication(a.id, "rejected"), "Declined.")}
                          >
                            Decline
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                  {(data.all_ministry_apps || []).length === 0 && (
                    <tr>
                      <td colSpan={5} style={{ textAlign: "center", color: "var(--text-muted)", padding: "2.5rem" }}>
                        No pending ministry volunteer applications.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* ── Profile Edit Form ── */
function ProfileEditForm({
  member,
  onSave,
}: {
  member: any;
  onSave: (d: any) => Promise<void>;
}) {
  const [form, setForm] = useState({
    full_name: member.full_name || "",
    email: member.email || "",
    address: member.address ?? "",
    age: String(member.age ?? ""),
  });
  const [saving, setSaving] = useState(false);

  const u = (k: string) => (e: any) =>
    setForm((p) => ({ ...p, [k]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    await onSave(form);
    setSaving(false);
  };

  return (
    <form onSubmit={handleSubmit} style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
      <div className="form-row">
        <div className="form-group">
          <label className="form-label">Full Name *</label>
          <input
            type="text"
            required
            value={form.full_name}
            onChange={u("full_name")}
            placeholder="First and Last Name"
          />
        </div>
        <div className="form-group">
          <label className="form-label">Email Address *</label>
          <input
            type="email"
            required
            value={form.email}
            onChange={u("email")}
            placeholder="your.email@example.com"
          />
        </div>
      </div>

      <div className="form-row">
        <div className="form-group">
          <label className="form-label">Age *</label>
          <input
            type="number"
            min={1}
            max={125}
            required
            value={form.age}
            onChange={u("age")}
            placeholder="e.g. 24"
          />
        </div>
        <div className="form-group">
          <label className="form-label">Address / Residence *</label>
          <input
            type="text"
            required
            value={form.address}
            onChange={u("address")}
            placeholder="City, Province or Barangay"
          />
        </div>
      </div>

      <div style={{ display: "flex", justifyContent: "flex-end", marginTop: "0.5rem" }}>
        <button className="btn btn-primary" type="submit" disabled={saving}>
          {saving ? "Saving Changes…" : "Save Changes"}
        </button>
      </div>
    </form>
  );
}

/* ── Create Event Form (Modal) ── */
function CreateEventForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({
    title: "",
    location: "",
    event_date: "",
    event_time: "",
    description: "",
    capacity: "",
  });
  const [file, setFile] = useState<File | null>(null);

  const u = (k: string) => (e: any) =>
    setForm((p) => ({ ...p, [k]: e.target.value }));

  const submit = async () => {
    const fd = new FormData();
    Object.entries(form).forEach(([k, v]) => fd.append(k, v));
    if (file) fd.append("banner_image", file);
    await onSave(fd);
    setOpen(false);
    setForm({ title: "", location: "", event_date: "", event_time: "", description: "", capacity: "" });
  };

  return (
    <>
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>
        + Create Event
      </button>
      {open && (
        <Modal title="Create Church Event" onClose={() => setOpen(false)}>
          {[
            ["title", "Event Title *", "text"],
            ["location", "Location / Venue *", "text"],
            ["event_date", "Date *", "date"],
            ["event_time", "Time *", "time"],
            ["capacity", "Capacity (Optional)", "number"],
          ].map(([k, l, t]) => (
            <div className="form-group mb-2" key={k}>
              <label className="form-label">{l}</label>
              <input type={t} value={(form as any)[k]} onChange={u(k)} />
            </div>
          ))}
          <div className="form-group mb-2">
            <label className="form-label">Description</label>
            <textarea
              rows={3}
              value={form.description}
              onChange={u("description")}
              style={{ resize: "vertical" }}
            />
          </div>
          <div className="form-group mb-3">
            <label className="form-label">Banner Image (Optional)</label>
            <input type="file" accept="image/*" onChange={(e) => setFile(e.target.files?.[0] ?? null)} />
          </div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>
            Publish Event
          </button>
        </Modal>
      )}
    </>
  );
}

/* ── Create D-Group Form (Modal) ── */
function CreateDGroupForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({
    name: "",
    leader_name: "",
    schedule: "",
    location: "",
    description: "",
  });
  const [file, setFile] = useState<File | null>(null);

  const u = (k: string) => (e: any) =>
    setForm((p) => ({ ...p, [k]: e.target.value }));

  const submit = async () => {
    const fd = new FormData();
    Object.entries(form).forEach(([k, v]) => fd.append(k, v));
    if (file) fd.append("image", file);
    await onSave(fd);
    setOpen(false);
    setForm({ name: "", leader_name: "", schedule: "", location: "", description: "" });
  };

  return (
    <>
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>
        + Create D-Group
      </button>
      {open && (
        <Modal title="Create Discipleship Group" onClose={() => setOpen(false)}>
          {[
            ["name", "Group Name *", "text"],
            ["leader_name", "Leader Name *", "text"],
            ["schedule", "Schedule *", "text"],
            ["location", "Meeting Location *", "text"],
          ].map(([k, l, t]) => (
            <div className="form-group mb-2" key={k}>
              <label className="form-label">{l}</label>
              <input type={t} value={(form as any)[k]} onChange={u(k)} />
            </div>
          ))}
          <div className="form-group mb-2">
            <label className="form-label">Description</label>
            <textarea
              rows={2}
              value={form.description}
              onChange={u("description")}
              style={{ resize: "vertical" }}
            />
          </div>
          <div className="form-group mb-3">
            <label className="form-label">Photo (Optional)</label>
            <input type="file" accept="image/*" onChange={(e) => setFile(e.target.files?.[0] ?? null)} />
          </div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>
            Create D-Group
          </button>
        </Modal>
      )}
    </>
  );
}

/* ── Create Ministry Form (Modal) ── */
function CreateMinistryForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ name: "", icon: "✦", description: "" });
  const [file, setFile] = useState<File | null>(null);

  const u = (k: string) => (e: any) =>
    setForm((p) => ({ ...p, [k]: e.target.value }));

  const submit = async () => {
    const fd = new FormData();
    Object.entries(form).forEach(([k, v]) => fd.append(k, v));
    if (file) fd.append("image", file);
    await onSave(fd);
    setOpen(false);
    setForm({ name: "", icon: "✦", description: "" });
  };

  return (
    <>
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>
        + Create Ministry
      </button>
      {open && (
        <Modal title="Create Ministry Team" onClose={() => setOpen(false)}>
          {[
            ["name", "Ministry Name *", "text"],
            ["icon", "Icon Emoji *", "text"],
          ].map(([k, l, t]) => (
            <div className="form-group mb-2" key={k}>
              <label className="form-label">{l}</label>
              <input type={t} value={(form as any)[k]} onChange={u(k)} />
            </div>
          ))}
          <div className="form-group mb-2">
            <label className="form-label">Description</label>
            <textarea
              rows={2}
              value={form.description}
              onChange={u("description")}
              style={{ resize: "vertical" }}
            />
          </div>
          <div className="form-group mb-3">
            <label className="form-label">Photo (Optional)</label>
            <input type="file" accept="image/*" onChange={(e) => setFile(e.target.files?.[0] ?? null)} />
          </div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>
            Create Ministry
          </button>
        </Modal>
      )}
    </>
  );
}
