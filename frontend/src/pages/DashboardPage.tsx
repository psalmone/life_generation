import { useEffect, useState } from "react";
import { getDashboard, updateProfile, uploadAvatar, removeAvatar, toggleMemberStatus, updateMemberRole } from "../api/dashboard";
import { rsvpEvent, cancelRsvp, createEvent, deleteEvent } from "../api/events";
import { joinDGroup, leaveDGroup, createDGroup, deleteDGroup } from "../api/dgroups";
import { applyMinistry, cancelMinistry, updateApplication, createMinistry, deleteMinistry } from "../api/ministries";
import type { DashboardData } from "../types";

/* ── Avatar helper ── */
function Avatar({ src, name, size = 48 }: { src?: string | null; name: string; size?: number }) {
  const style: React.CSSProperties = {
    width: size, height: size, borderRadius: "50%",
    objectFit: "cover",
    background: "var(--orange-soft)",
    border: "2px solid var(--orange-border)",
    display: "flex", alignItems: "center", justifyContent: "center",
    fontSize: size * 0.38, fontWeight: 800, color: "var(--orange-primary)",
    flexShrink: 0,
  };
  if (src) return <img src={`/${src}`} style={{ ...style, display: "block" }} alt={name} />;
  return <div style={style}>{name?.[0]?.toUpperCase() ?? "?"}</div>;
}

/* ── Info row ── */
function InfoRow({ label, value }: { label: string; value?: string | number | null }) {
  return (
    <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", padding: "0.55rem 0", borderBottom: "1px solid var(--card-border)" }}>
      <span style={{ color: "var(--text-muted)", fontSize: "0.83rem", fontWeight: 600 }}>{label}</span>
      <span style={{ fontWeight: 700, fontSize: "0.9rem", color: "var(--text-main)" }}>{value ?? "—"}</span>
    </div>
  );
}

/* ── Modal wrapper ── */
function Modal({ title, onClose, children }: { title: string; onClose: () => void; children: React.ReactNode }) {
  return (
    <div className="modal-overlay" onClick={e => e.target === e.currentTarget && onClose()}>
      <div className="modal">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "1.5rem" }}>
          <h2 style={{ fontSize: "1.15rem", fontWeight: 800, letterSpacing: "-0.01em" }}>{title}</h2>
          <button onClick={onClose} style={{ background: "none", border: "none", fontSize: "1.5rem", color: "var(--text-muted)", lineHeight: 1, cursor: "pointer" }}>×</button>
        </div>
        {children}
      </div>
    </div>
  );
}

/* ── Main Dashboard ── */
export default function DashboardPage() {
  const [data, setData] = useState<DashboardData | null>(null);
  const [tab, setTab] = useState("profile");
  const [msg, setMsg] = useState<{ type: "success" | "error"; text: string } | null>(null);
  const [loading, setLoading] = useState(true);

  const load = () => getDashboard().then(setData).catch(() => setMsg({ type: "error", text: "Failed to load dashboard." })).finally(() => setLoading(false));
  useEffect(() => { load(); }, []);

  const flash = (type: "success" | "error", text: string) => { setMsg({ type, text }); setTimeout(() => setMsg(null), 4500); };
  const run = async (fn: () => Promise<any>, successMsg: string) => {
    try { await fn(); flash("success", successMsg); load(); }
    catch (e: any) { flash("error", e.response?.data?.error || "Something went wrong."); }
  };

  if (loading) return (
    <div style={{ minHeight: "60vh", display: "flex", alignItems: "center", justifyContent: "center" }}>
      <div style={{ textAlign: "center" }}>
        <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>✦</div>
        <p style={{ color: "var(--text-muted)", fontWeight: 600 }}>Loading your dashboard…</p>
      </div>
    </div>
  );
  if (!data) return <div className="container page"><div className="alert alert-error">Failed to load dashboard.</div></div>;

  const { user, member, current_dgroup, all_dgroups, current_ministry, ministries, user_apps, upcoming_events, user_rsvps, prayer_requests } = data;
  const isAdmin = user.role === "admin";
  const rsvpMap = Object.fromEntries((user_rsvps || []).map((r: any) => [r.event_id, r]));

  const tabs = [
    { id: "profile",    label: "Profile" },
    { id: "events",     label: "Events" },
    { id: "dgroups",    label: "D-Groups" },
    { id: "ministries", label: "Ministries" },
    ...(isAdmin ? [
      { id: "members",          label: "Members" },
      { id: "admin-events",     label: "Manage Events" },
      { id: "admin-dgroups",    label: "Manage D-Groups" },
      { id: "admin-ministries", label: "Manage Ministries" },
      { id: "admin-apps",       label: "Applications" },
    ] : []),
  ];

  return (
    <div className="container page">
      {/* ── Dashboard header card ── */}
      <div className="dashboard-header mb-3">
        <div style={{ display: "flex", alignItems: "center", gap: "1.25rem", position: "relative", zIndex: 1 }}>
          <Avatar src={member.avatar} name={member.full_name} size={60} />
          <div>
            <div style={{ fontSize: "0.78rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: "rgba(255,255,255,0.7)", marginBottom: 4 }}>
              Member Portal
            </div>
            <h1 style={{ fontSize: "1.5rem", fontWeight: 800, letterSpacing: "-0.02em", color: "#fff", lineHeight: 1.2 }}>
              {member.full_name}
            </h1>
            <div style={{ display: "flex", alignItems: "center", gap: 8, marginTop: 6, flexWrap: "wrap" }}>
              <span className="member-id-pill">{member.member_id}</span>
              {isAdmin && <span style={{ fontSize: "0.72rem", fontWeight: 700, padding: "2px 8px", background: "rgba(255,255,255,0.2)", borderRadius: 9999, color: "#fff" }}>ADMIN</span>}
              {current_dgroup && <span style={{ fontSize: "0.72rem", fontWeight: 700, padding: "2px 8px", background: "rgba(255,255,255,0.15)", borderRadius: 9999, color: "rgba(255,255,255,0.9)" }}>📍 {current_dgroup.name}</span>}
            </div>
          </div>
        </div>
      </div>

      {msg && <div className={`alert alert-${msg.type} mb-2`}>{msg.text}</div>}

      {/* ── Tabs ── */}
      <div className="tabs mb-3" style={{ overflowX: "auto" }}>
        {tabs.map(t => (
          <button key={t.id} className={`tab-btn ${tab === t.id ? "active" : ""}`} onClick={() => setTab(t.id)}>
            {t.label}
          </button>
        ))}
      </div>

      {/* ══════════ PROFILE ══════════ */}
      {tab === "profile" && (
        <div className="grid-2">
          <div>
            <div className="card">
              <div style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: "1.25rem" }}>
                <div style={{ width: 36, height: 36, borderRadius: 10, background: "var(--orange-soft)", border: "1px solid var(--orange-border)", color: "var(--orange-primary)", display: "flex", alignItems: "center", justifyContent: "center", fontSize: "1.1rem" }}>👤</div>
                <div className="card-title" style={{ marginBottom: 0 }}>Member Information</div>
              </div>
              <InfoRow label="Member ID" value={member.member_id} />
              <InfoRow label="Full Name" value={member.full_name} />
              <InfoRow label="Email" value={member.email} />
              <InfoRow label="Age" value={member.age} />
              <InfoRow label="Address" value={member.address} />
              <InfoRow label="D-Group" value={current_dgroup?.name} />
              <InfoRow label="Ministry" value={current_ministry?.name} />
              <InfoRow label="Joined" value={member.joined_at ? new Date(member.joined_at).toLocaleDateString("en-PH", { month: "long", day: "numeric", year: "numeric" }) : undefined} />
            </div>
          </div>

          <div style={{ display: "flex", flexDirection: "column", gap: "1.25rem" }}>
            <ProfileEditForm member={member} onSave={async (d) => { await run(() => updateProfile(d), "Profile updated!"); }} />

            <div className="card">
              <div className="card-title mb-2">Profile Photo</div>
              <div style={{ display: "flex", gap: "1.25rem", alignItems: "center" }}>
                <Avatar src={member.avatar} name={member.full_name} size={72} />
                <div style={{ display: "flex", flexDirection: "column", gap: "0.5rem" }}>
                  <label className="btn btn-outline btn-sm" style={{ cursor: "pointer" }}>
                    Upload Photo
                    <input type="file" accept="image/*" style={{ display: "none" }} onChange={async e => {
                      if (e.target.files?.[0]) await run(() => uploadAvatar(e.target.files![0]), "Photo updated!");
                    }} />
                  </label>
                  {member.avatar && (
                    <button className="btn btn-danger btn-sm" onClick={() => run(removeAvatar, "Photo removed.")}>Remove</button>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* ══════════ EVENTS ══════════ */}
      {tab === "events" && (
        <div>
          <div className="flex-between mb-3">
            <div>
              <span className="section-tag" style={{ display: "block", marginBottom: 2 }}>Gather With Us</span>
              <h2 style={{ fontSize: "1.2rem", fontWeight: 800 }}>Upcoming Events</h2>
            </div>
          </div>
          <div className="grid-3">
            {upcoming_events.map(e => {
              const myRsvp = rsvpMap[e.id];
              const isAttending = myRsvp?.rsvp_status === "attending";
              return (
                <div className="card" key={e.id} style={isAttending ? { borderColor: "var(--blue-primary)", boxShadow: "0 0 0 2px rgba(59,130,246,0.1)" } : {}}>
                  {e.banner_image && <img src={`/${e.banner_image}`} className="event-img" alt={e.title} />}
                  <div className="card-tag">
                    {new Date(e.event_date).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })}
                  </div>
                  <div className="card-title">{e.title}</div>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>🕐 {e.event_time}</p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 10 }}>📍 {e.location}</p>
                  {e.capacity && <p style={{ color: "var(--text-light)", fontSize: "0.8rem", marginBottom: 10 }}>👥 {e.rsvp_count ?? 0}/{e.capacity} attending</p>}
                  <div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
                    {isAttending ? (
                      <>
                        <span className="badge badge-blue">✓ RSVP'd</span>
                        <button className="btn btn-danger btn-sm" onClick={() => run(() => cancelRsvp(e.id), "RSVP cancelled.")}>Cancel</button>
                      </>
                    ) : (
                      <button className="btn btn-primary btn-sm" onClick={() => run(() => rsvpEvent(e.id), `RSVP confirmed!`)}>RSVP Now</button>
                    )}
                    {myRsvp?.checked_in === 1 && <span className="badge badge-success">✓ Checked In</span>}
                  </div>
                </div>
              );
            })}
            {upcoming_events.length === 0 && (
              <p style={{ color: "var(--text-muted)", gridColumn: "1/-1", textAlign: "center", padding: "2rem" }}>No upcoming events. Check back soon!</p>
            )}
          </div>
        </div>
      )}

      {/* ══════════ DGROUPS ══════════ */}
      {tab === "dgroups" && (
        <div>
          {current_dgroup ? (
            <div className="card mb-3" style={{ borderColor: "var(--orange-primary)", boxShadow: "0 0 0 2px rgba(249,115,22,0.1)" }}>
              <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start", gap: "1rem", flexWrap: "wrap" }}>
                <div>
                  <span className="badge badge-accent mb-2" style={{ display: "inline-block" }}>Your D-Group ✓</span>
                  <div className="card-title">{current_dgroup.name}</div>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.88rem", marginBottom: 4 }}>👤 Led by {current_dgroup.leader_name}</p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.88rem", marginBottom: 4 }}>🕐 {current_dgroup.schedule} · 📍 {current_dgroup.location}</p>
                  <p style={{ color: "var(--text-light)", fontSize: "0.82rem" }}>👥 {current_dgroup.member_count ?? 0} members</p>
                </div>
                {current_dgroup.image && <img src={`/${current_dgroup.image}`} style={{ width: 100, height: 100, objectFit: "cover", borderRadius: "var(--radius-md)" }} alt={current_dgroup.name} />}
              </div>
              <button className="btn btn-danger btn-sm mt-2" onClick={() => run(leaveDGroup, "Left D-Group.")}>Leave D-Group</button>
            </div>
          ) : (
            <>
              <div className="alert alert-info mb-3">You are not in a D-Group yet. Join one below to grow in community.</div>
              <div className="grid-3">
                {all_dgroups.map(g => (
                  <div className="card" key={g.id}>
                    {g.image && <img src={`/${g.image}`} className="event-img" alt={g.name} />}
                    <div className="card-title">{g.name}</div>
                    <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>👤 {g.leader_name}</p>
                    <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>🕐 {g.schedule}</p>
                    <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 6 }}>📍 {g.location}</p>
                    <p style={{ color: "var(--text-light)", fontSize: "0.8rem", marginBottom: 12 }}>👥 {g.member_count ?? 0} members</p>
                    <button className="btn btn-primary btn-sm" onClick={() => run(() => joinDGroup(g.id), `Joined ${g.name}!`)}>Join This Group</button>
                  </div>
                ))}
              </div>
            </>
          )}
        </div>
      )}

      {/* ══════════ MINISTRIES ══════════ */}
      {tab === "ministries" && (
        <div>
          {current_ministry && (
            <div className="alert alert-success mb-3">✓ You are an active member of <strong>{current_ministry.name}</strong></div>
          )}
          {user_apps.length > 0 && (
            <div className="card mb-3">
              <div className="card-title mb-2">Your Applications</div>
              {user_apps.map(a => (
                <div key={a.id} className="flex-between" style={{ padding: "0.5rem 0", borderBottom: "1px solid var(--card-border)" }}>
                  <span style={{ fontWeight: 600, fontSize: "0.9rem" }}>{a.icon} {a.ministry_name}</span>
                  <span className={`badge badge-${a.status === "approved" ? "success" : a.status === "rejected" ? "danger" : "accent"}`}>
                    {a.status}
                  </span>
                </div>
              ))}
            </div>
          )}
          <div className="grid-3">
            {ministries.map(m => (
              <div className="card" key={m.id}>
                {m.image && <img src={`/${m.image}`} className="event-img" alt={m.name} />}
                {m.icon && <div style={{ fontSize: "1.8rem", marginBottom: 8 }}>{m.icon}</div>}
                <div className="card-title">{m.name}</div>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55, marginBottom: "1rem" }}>{m.description?.slice(0, 100)}</p>
                <div>
                  {m.application_status === "approved" ? (
                    <span className="badge badge-success">✓ Active Member</span>
                  ) : m.application_status === "pending" ? (
                    <div style={{ display: "flex", gap: 8, alignItems: "center" }}>
                      <span className="badge badge-accent">Pending</span>
                      <button className="btn btn-outline btn-sm" onClick={() => run(() => cancelMinistry(m.id), "Application withdrawn.")}>Withdraw</button>
                    </div>
                  ) : (
                    <button className="btn btn-primary btn-sm" onClick={() => run(() => applyMinistry(m.id, ""), `Applied to ${m.name}!`)}>Apply to Volunteer</button>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ══════════ ADMIN: MEMBERS ══════════ */}
      {tab === "members" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <h2 style={{ fontSize: "1.2rem", fontWeight: 800 }}>Member Directory ({data.total_members})</h2>
          </div>
          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table style={{ width: "100%", borderCollapse: "collapse", fontSize: "0.88rem" }}>
                <thead>
                  <tr style={{ background: "var(--bg-soft)" }}>
                    {["Member", "Member ID", "Role", "Ministry", "D-Group", "Status", "Action"].map(h => (
                      <th key={h} style={{ padding: "10px 14px", textAlign: "left", fontWeight: 800, fontSize: "0.76rem", textTransform: "uppercase", letterSpacing: "0.05em", color: "var(--text-muted)", whiteSpace: "nowrap" }}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_members || []).map(m => (
                    <tr key={m.id} style={{ borderTop: "1px solid var(--card-border)" }}>
                      <td style={{ padding: "10px 14px" }}>
                        <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
                          <Avatar src={m.avatar} name={m.full_name} size={32} />
                          <div>
                            <div style={{ fontWeight: 700 }}>{m.full_name}</div>
                            <div style={{ color: "var(--text-muted)", fontSize: "0.78rem" }}>{m.email}</div>
                          </div>
                        </div>
                      </td>
                      <td style={{ padding: "10px 14px" }}><span className="badge badge-accent">{m.member_id}</span></td>
                      <td style={{ padding: "10px 14px" }}>
                        <select style={{ width: "auto", fontSize: "0.8rem", padding: "4px 8px" }} defaultValue={m.role}
                          onChange={e => run(() => updateMemberRole(m.user_id!, e.target.value), "Role updated.")}>
                          {["user","moderator","admin"].map(r => <option key={r} value={r}>{r}</option>)}
                        </select>
                      </td>
                      <td style={{ padding: "10px 14px", color: "var(--text-muted)" }}>{m.ministry_name || "—"}</td>
                      <td style={{ padding: "10px 14px", color: "var(--text-muted)" }}>{m.dgroup_name || "—"}</td>
                      <td style={{ padding: "10px 14px" }}>
                        <span className={`badge badge-${m.is_active ? "success" : "danger"}`}>{m.is_active ? "Active" : "Inactive"}</span>
                      </td>
                      <td style={{ padding: "10px 14px" }}>
                        <button className="btn btn-outline btn-sm" onClick={() => run(() => toggleMemberStatus(m.id!), "Status updated.")}>
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

      {/* ══════════ ADMIN: MANAGE EVENTS ══════════ */}
      {tab === "admin-events" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <h2 style={{ fontSize: "1.2rem", fontWeight: 800 }}>Manage Events</h2>
            <CreateEventForm onSave={async (fd) => { await run(() => createEvent(fd), "Event created!"); }} />
          </div>
          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table style={{ width: "100%", borderCollapse: "collapse", fontSize: "0.88rem" }}>
                <thead>
                  <tr style={{ background: "var(--bg-soft)" }}>
                    {["Event", "Date & Time", "Location", "RSVPs", "Action"].map(h => (
                      <th key={h} style={{ padding: "10px 14px", textAlign: "left", fontWeight: 800, fontSize: "0.76rem", textTransform: "uppercase", letterSpacing: "0.05em", color: "var(--text-muted)" }}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_events_admin || []).map(e => (
                    <tr key={e.id} style={{ borderTop: "1px solid var(--card-border)" }}>
                      <td style={{ padding: "10px 14px", fontWeight: 700 }}>{e.title}</td>
                      <td style={{ padding: "10px 14px", color: "var(--text-muted)" }}>{new Date(e.event_date).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })} · {e.event_time}</td>
                      <td style={{ padding: "10px 14px", color: "var(--text-muted)" }}>{e.location}</td>
                      <td style={{ padding: "10px 14px" }}>{e.rsvp_count ?? 0}</td>
                      <td style={{ padding: "10px 14px" }}>
                        <button className="btn btn-danger btn-sm" onClick={() => { if (confirm("Delete this event?")) run(() => deleteEvent(e.id), "Event deleted."); }}>Delete</button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* ══════════ ADMIN: DGROUPS ══════════ */}
      {tab === "admin-dgroups" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <h2 style={{ fontSize: "1.2rem", fontWeight: 800 }}>Manage D-Groups</h2>
            <CreateDGroupForm onSave={async (fd) => { await run(() => createDGroup(fd), "D-Group created!"); }} />
          </div>
          <div className="grid-3">
            {(data.all_dgroups_admin || []).map(g => (
              <div className="card" key={g.id}>
                <div className="card-title">{g.name}</div>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 4 }}>👤 {g.leader_name}</p>
                <p style={{ color: "var(--text-light)", fontSize: "0.8rem", marginBottom: 12 }}>👥 {g.member_count ?? 0} members</p>
                <button className="btn btn-danger btn-sm" onClick={() => { if (confirm("Delete this D-Group?")) run(() => deleteDGroup(g.id), "D-Group deleted."); }}>Delete</button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ══════════ ADMIN: MINISTRIES ══════════ */}
      {tab === "admin-ministries" && isAdmin && (
        <div>
          <div className="flex-between mb-3">
            <h2 style={{ fontSize: "1.2rem", fontWeight: 800 }}>Manage Ministries</h2>
            <CreateMinistryForm onSave={async (fd) => { await run(() => createMinistry(fd), "Ministry created!"); }} />
          </div>
          <div className="grid-3">
            {(data.all_ministries_admin || []).map(m => (
              <div className="card" key={m.id}>
                {m.icon && <div style={{ fontSize: "1.8rem", marginBottom: 8 }}>{m.icon}</div>}
                <div className="card-title">{m.name}</div>
                <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: 12 }}>
                  👥 {m.member_count ?? 0} members · 📋 {m.pending_apps_count ?? 0} pending
                </p>
                <button className="btn btn-danger btn-sm" onClick={() => { if (confirm("Delete this ministry?")) run(() => deleteMinistry(m.id), "Ministry deleted."); }}>Delete</button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* ══════════ ADMIN: APPLICATIONS ══════════ */}
      {tab === "admin-apps" && isAdmin && (
        <div>
          <h2 style={{ fontSize: "1.2rem", fontWeight: 800, marginBottom: "1.5rem" }}>Ministry Applications</h2>
          <div className="card" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table style={{ width: "100%", borderCollapse: "collapse", fontSize: "0.88rem" }}>
                <thead>
                  <tr style={{ background: "var(--bg-soft)" }}>
                    {["Applicant", "Ministry", "Message", "Status", "Actions"].map(h => (
                      <th key={h} style={{ padding: "10px 14px", textAlign: "left", fontWeight: 800, fontSize: "0.76rem", textTransform: "uppercase", letterSpacing: "0.05em", color: "var(--text-muted)" }}>{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {(data.all_ministry_apps || []).map(a => (
                    <tr key={a.id} style={{ borderTop: "1px solid var(--card-border)" }}>
                      <td style={{ padding: "10px 14px" }}>
                        <div style={{ fontWeight: 700 }}>{a.member_name}</div>
                        <div style={{ color: "var(--text-muted)", fontSize: "0.78rem" }}>{a.member_id}</div>
                      </td>
                      <td style={{ padding: "10px 14px" }}>{a.icon} {a.ministry_name}</td>
                      <td style={{ padding: "10px 14px", color: "var(--text-muted)", maxWidth: 200 }}>{a.message || "—"}</td>
                      <td style={{ padding: "10px 14px" }}>
                        <span className={`badge badge-${a.status === "approved" ? "success" : a.status === "rejected" ? "danger" : "accent"}`}>{a.status}</span>
                      </td>
                      <td style={{ padding: "10px 14px" }}>
                        <div style={{ display: "flex", gap: 6 }}>
                          <button className="btn btn-success btn-sm" onClick={() => run(() => updateApplication(a.id, "approved"), "Approved! 🎉")}>Approve</button>
                          <button className="btn btn-danger btn-sm" onClick={() => run(() => updateApplication(a.id, "rejected"), "Rejected.")}>Reject</button>
                        </div>
                      </td>
                    </tr>
                  ))}
                  {(data.all_ministry_apps || []).length === 0 && (
                    <tr><td colSpan={5} style={{ textAlign: "center", color: "var(--text-muted)", padding: "2rem" }}>No pending applications.</td></tr>
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

/* ── Profile edit form ── */
function ProfileEditForm({ member, onSave }: { member: any; onSave: (d: any) => Promise<void> }) {
  const [form, setForm] = useState({ full_name: member.full_name, email: member.email, address: member.address ?? "", age: String(member.age ?? "") });
  const [saving, setSaving] = useState(false);
  const u = (k: string) => (e: any) => setForm(p => ({ ...p, [k]: e.target.value }));
  return (
    <div className="card">
      <div className="card-title mb-2">Edit Profile</div>
      <div style={{ display: "flex", flexDirection: "column", gap: "0.75rem" }}>
        {[["full_name","Full Name","text"],["email","Email","email"],["age","Age","number"],["address","Address","text"]].map(([k,l,t]) => (
          <div className="form-group" key={k}>
            <label className="form-label">{l}</label>
            <input type={t} value={(form as any)[k]} onChange={u(k)} />
          </div>
        ))}
        <button className="btn btn-primary" disabled={saving} onClick={async () => { setSaving(true); await onSave(form); setSaving(false); }}>
          {saving ? "Saving…" : "Save Changes"}
        </button>
      </div>
    </div>
  );
}

/* ── Create event form (modal) ── */
function CreateEventForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ title: "", location: "", event_date: "", event_time: "", description: "", capacity: "" });
  const [file, setFile] = useState<File | null>(null);
  const u = (k: string) => (e: any) => setForm(p => ({ ...p, [k]: e.target.value }));
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
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>+ Create Event</button>
      {open && (
        <Modal title="Create Event" onClose={() => setOpen(false)}>
          {[["title","Title *","text"],["location","Location *","text"],["event_date","Date *","date"],["event_time","Time *","time"],["capacity","Capacity (optional)","number"]].map(([k,l,t]) => (
            <div className="form-group mb-2" key={k}><label className="form-label">{l}</label><input type={t} value={(form as any)[k]} onChange={u(k)} /></div>
          ))}
          <div className="form-group mb-2"><label className="form-label">Description</label><textarea rows={3} value={form.description} onChange={u("description")} style={{ resize: "vertical" }} /></div>
          <div className="form-group mb-3"><label className="form-label">Banner Image (optional)</label><input type="file" accept="image/*" onChange={e => setFile(e.target.files?.[0] ?? null)} /></div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>Create Event</button>
        </Modal>
      )}
    </>
  );
}

/* ── Create D-Group form (modal) ── */
function CreateDGroupForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ name: "", leader_name: "", schedule: "", location: "", description: "" });
  const [file, setFile] = useState<File | null>(null);
  const u = (k: string) => (e: any) => setForm(p => ({ ...p, [k]: e.target.value }));
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
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>+ Create D-Group</button>
      {open && (
        <Modal title="Create D-Group" onClose={() => setOpen(false)}>
          {[["name","Group Name *","text"],["leader_name","Leader Name *","text"],["schedule","Schedule *","text"],["location","Location *","text"]].map(([k,l,t]) => (
            <div className="form-group mb-2" key={k}><label className="form-label">{l}</label><input type={t} value={(form as any)[k]} onChange={u(k)} /></div>
          ))}
          <div className="form-group mb-2"><label className="form-label">Description</label><textarea rows={2} value={form.description} onChange={u("description")} /></div>
          <div className="form-group mb-3"><label className="form-label">Photo (optional)</label><input type="file" accept="image/*" onChange={e => setFile(e.target.files?.[0] ?? null)} /></div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>Create D-Group</button>
        </Modal>
      )}
    </>
  );
}

/* ── Create Ministry form (modal) ── */
function CreateMinistryForm({ onSave }: { onSave: (fd: FormData) => Promise<void> }) {
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ name: "", icon: "✦", description: "" });
  const [file, setFile] = useState<File | null>(null);
  const u = (k: string) => (e: any) => setForm(p => ({ ...p, [k]: e.target.value }));
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
      <button className="btn btn-primary btn-sm" onClick={() => setOpen(true)}>+ Create Ministry</button>
      {open && (
        <Modal title="Create Ministry" onClose={() => setOpen(false)}>
          {[["name","Ministry Name *","text"],["icon","Icon (emoji)","text"]].map(([k,l,t]) => (
            <div className="form-group mb-2" key={k}><label className="form-label">{l}</label><input type={t} value={(form as any)[k]} onChange={u(k)} /></div>
          ))}
          <div className="form-group mb-2"><label className="form-label">Description</label><textarea rows={2} value={form.description} onChange={u("description")} /></div>
          <div className="form-group mb-3"><label className="form-label">Photo (optional)</label><input type="file" accept="image/*" onChange={e => setFile(e.target.files?.[0] ?? null)} /></div>
          <button className="btn btn-primary" style={{ width: "100%" }} onClick={submit}>Create Ministry</button>
        </Modal>
      )}
    </>
  );
}
