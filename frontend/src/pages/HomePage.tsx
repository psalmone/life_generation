import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { getPublicHome } from "../api/auth";
import { useAuth } from "../context/AuthContext";

export default function HomePage() {
  const { isAuthenticated } = useAuth();
  const [data, setData] = useState<any>(null);

  useEffect(() => {
    getPublicHome().then(setData).catch(() => {});
  }, []);

  return (
    <div>
      {/* ── Hero Section ── */}
      <section className="hero-section">
        <div className="hero-inner">
          <div className="hero-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#fb923c">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            1 Peter 2:9 · A Chosen Generation · Called Into His Light
          </div>

          <h1 className="hero-title">
            Empowering Generations Through a{" "}
            <span className="hero-accent">Kindled Passion in Christ</span>
          </h1>

          <p className="hero-sub">
            Welcome to Life Generation Minsu Christian Community. We are a family rooted in Scripture,
            passionate in worship, and dedicated to raising generations filled with faith, hope, and love.
          </p>

          <div className="flex-center gap-2 mt-3" style={{ justifyContent: "center", flexWrap: "wrap" }}>
            {isAuthenticated ? (
              <Link to="/dashboard" className="btn btn-primary btn-lg">
                <svg width="17" height="17" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                View Member Portal
              </Link>
            ) : (
              <>
                <Link to="/register" className="btn btn-primary btn-lg">Register for Membership</Link>
                <Link
                  to="/login"
                  className="btn btn-lg"
                  style={{ background: "rgba(255,255,255,0.12)", color: "#fff", border: "1px solid rgba(255,255,255,0.25)", padding: "0.85rem 2rem" }}
                >
                  Access Member Portal →
                </Link>
              </>
            )}
            <Link to="/prayer-wall" className="btn btn-blue btn-lg">
              <svg width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              Prayer Wall
            </Link>
          </div>

          {/* Verse card */}
          <div className="hero-verse">
            <blockquote>
              "I came that they may have life, and have it abundantly. A new commandment I give to you,
              that you love one another: just as I have loved you, you also are to love one another."
            </blockquote>
            <cite>— John 10:10, John 13:34</cite>
          </div>

          {/* Social bar */}
          <div className="hero-social-bar">
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#fed7aa", textTransform: "uppercase", letterSpacing: "0.06em" }}>
              Connect With Us
            </span>
            <a href="https://www.facebook.com/radicalledcalapan" target="_blank" rel="noopener noreferrer" className="hero-social-link fb">
              <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              Facebook
            </a>
            <a href="https://www.instagram.com/lifegencalapan" target="_blank" rel="noopener noreferrer" className="hero-social-link ig">
              <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              Instagram
            </a>
            <a href="https://www.tiktok.com/@life.generation7" target="_blank" rel="noopener noreferrer" className="hero-social-link tk">
              <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 00-1-.08A6.34 6.34 0 003 15.66a6.34 6.34 0 0010.82 4.47 6.27 6.27 0 001.89-4.47V8.69a8.18 8.18 0 004.88 1.6V6.84a4.86 4.86 0 01-1-.15z"/></svg>
              TikTok
            </a>
          </div>
        </div>
      </section>

      {/* ── Stats Bar ── */}
      {data && (
        <section className="stats-bar">
          <div className="stats-row">
            {[
              { n: data.total_members ?? "150+", l: "Registered Members" },
              { n: "4+",      l: "Active Ministries" },
              { n: "Weekly", l: "D-Group Gatherings" },
              { n: "100%",   l: "Christ-Centered Mission" },
            ].map((s) => (
              <div key={s.l}>
                <div className="stat-number">{s.n}</div>
                <div className="stat-label">{s.l}</div>
              </div>
            ))}
          </div>
        </section>
      )}

      {/* ── Events Section ── */}
      {data?.upcoming_events?.length > 0 && (
        <section style={{ padding: "64px 24px" }}>
          <div style={{ maxWidth: 1200, margin: "0 auto" }}>
            <div className="section-header">
              <span className="section-tag">Gather With Us</span>
              <h2 className="section-title">Upcoming Church Events</h2>
              <p className="section-desc">Join our Sunday celebration services, youth fellowships, and special conferences.</p>
            </div>
            <div className="grid-3">
              {data.upcoming_events.map((e: any) => (
                <div className="card" key={e.id}>
                  {e.banner_image && (
                    <img src={`/${e.banner_image}`} alt={e.title} className="event-img" />
                  )}
                  <div className="card-tag">
                    <svg width="11" height="11" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {new Date(e.event_date).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })}
                  </div>
                  <div className="card-title">{e.title}</div>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: "6px" }}>🕐 {e.event_time}</p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem" }}>📍 {e.location}</p>
                  {!isAuthenticated && (
                    <Link to="/login" className="btn btn-primary btn-sm mt-2">RSVP Now →</Link>
                  )}
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* ── D-Groups ── */}
      {data?.dgroups?.length > 0 && (
        <section style={{ padding: "64px 24px", background: "var(--bg-soft)" }}>
          <div style={{ maxWidth: 1200, margin: "0 auto" }}>
            <div className="section-header">
              <span className="section-tag">Grow Together</span>
              <h2 className="section-title">Discipleship Groups</h2>
              <p className="section-desc">Join a D-Group and experience life-changing community every week.</p>
            </div>
            <div className="grid-3">
              {data.dgroups.map((g: any) => (
                <div className="card" key={g.id}>
                  {g.image && <img src={`/${g.image}`} alt={g.name} className="event-img" />}
                  <div className="card-title">{g.name}</div>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: "4px" }}>👤 {g.leader_name}</p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", marginBottom: "4px" }}>🕐 {g.schedule}</p>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem" }}>📍 {g.location}</p>
                  <p style={{ color: "var(--text-light)", fontSize: "0.8rem", marginTop: "6px" }}>👥 {g.member_count ?? 0} members</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* ── Ministries ── */}
      {data?.ministries?.length > 0 && (
        <section style={{ padding: "64px 24px" }}>
          <div style={{ maxWidth: 1200, margin: "0 auto" }}>
            <div className="section-header">
              <span className="section-tag">Serve & Grow</span>
              <h2 className="section-title">Ministry Teams</h2>
              <p className="section-desc">Discover your calling and serve in one of our vibrant ministry teams.</p>
            </div>
            <div className="grid-4">
              {data.ministries.map((m: any) => (
                <div className="card" key={m.id}>
                  <div style={{ fontSize: "2rem", marginBottom: "10px" }}>{m.icon}</div>
                  <div className="card-title">{m.name}</div>
                  <p style={{ color: "var(--text-muted)", fontSize: "0.84rem", lineHeight: 1.55 }}>{m.description}</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* ── Prayer Wall preview ── */}
      {data?.prayer_requests?.length > 0 && (
        <section style={{ padding: "64px 24px", background: "var(--bg-soft)" }}>
          <div style={{ maxWidth: 1200, margin: "0 auto" }}>
            <div className="section-header">
              <span className="section-tag">Pray Together</span>
              <h2 className="section-title">Community Prayer Wall</h2>
              <p className="section-desc">Lift up one another in prayer. Submit your request and let the community stand with you.</p>
            </div>
            <div className="grid-3">
              {data.prayer_requests.map((r: any) => (
                <div className="prayer-card" key={r.id}>
                  <div>
                    <div className="flex-center gap-1 mb-2">
                      <div className="prayer-avatar">{(r.member_name ?? "A").charAt(0).toUpperCase()}</div>
                      <div>
                        <div style={{ fontSize: "0.86rem", fontWeight: 700, color: "var(--text-main)" }}>{r.member_name}</div>
                        <div style={{ fontSize: "0.72rem", color: "var(--text-light)" }}>
                          {new Date(r.created_at).toLocaleDateString("en-PH", { month: "short", day: "numeric", year: "numeric" })}
                        </div>
                      </div>
                    </div>
                    <div style={{ fontSize: "0.98rem", fontWeight: 800, marginBottom: "6px" }}>{r.title}</div>
                    <p style={{ fontSize: "0.84rem", color: "var(--text-muted)", lineHeight: 1.55 }}>{r.body}</p>
                  </div>
                  <div className="mt-2">
                    <span className={`badge ${r.status === "answered" ? "badge-success" : "badge-blue"}`}>
                      {r.status === "answered" ? "✓ Answered" : "🙏 Praying"}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* ── CTA ── */}
      <section style={{ padding: "64px 24px" }}>
        <div style={{ maxWidth: 1200, margin: "0 auto" }}>
          <div className="cta-banner">
            <h2>Ready to Begin Your Journey?</h2>
            <p>
              Join Life Generation Minsu Christian Community. Find your D-Group, serve in a ministry,
              and grow alongside a family that walks in faith together.
            </p>
            <div className="flex-center gap-2" style={{ justifyContent: "center", flexWrap: "wrap" }}>
              <Link to="/register" className="btn btn-lg" style={{ background: "#fff", color: "var(--orange-primary)", fontWeight: 800, boxShadow: "0 4px 12px rgba(0,0,0,0.15)" }}>
                Register for Membership
              </Link>
              <Link to="/login" className="btn btn-lg" style={{ background: "rgba(255,255,255,0.15)", color: "#fff", border: "1px solid rgba(255,255,255,0.3)" }}>
                Access Member Portal →
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* ── Footer ── */}
      <footer className="site-footer">
        <div className="footer-grid">
          <div className="footer-col">
            <div style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: 14 }}>
              <div className="nav-brand-icon" style={{ width: 36, height: 36, background: "#fff", border: "1px solid var(--card-border)", overflow: "hidden", padding: 2 }}>
              <img src="/assets/images/lifegen-logo.png" alt="Life Generation" style={{ width: "100%", height: "100%", objectFit: "contain" }} />
            </div>
              <div>
                <div style={{ fontWeight: 800, fontSize: "1rem" }}>Life Generation</div>
                <div style={{ fontSize: "0.66rem", fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.08em", color: "var(--orange-primary)" }}>Christian Fellowship</div>
              </div>
            </div>
            <p>A community of faith, worship, and discipleship rooted in Scripture and passionate in love.</p>
            <div style={{ display: "flex", gap: 8, marginTop: 14 }}>
              {[
                { href: "https://www.facebook.com/radicalledcalapan", label: "Facebook", path: "M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" },
              ].map((s) => (
                <a key={s.label} href={s.href} target="_blank" rel="noopener noreferrer"
                  style={{ width: 36, height: 36, borderRadius: 10, background: "var(--bg-soft)", border: "1px solid var(--card-border)", display: "flex", alignItems: "center", justifyContent: "center", color: "var(--text-main)", transition: "all 0.2s" }}>
                  <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d={s.path}/></svg>
                </a>
              ))}
            </div>
          </div>
          <div className="footer-col">
            <h4>Community</h4>
            <ul className="footer-links">
              <li><Link to="/dgroups">D-Groups</Link></li>
              <li><Link to="/ministries">Ministries</Link></li>
              <li><Link to="/events">Events</Link></li>
              <li><Link to="/prayer-wall">Prayer Wall</Link></li>
            </ul>
          </div>
          <div className="footer-col">
            <h4>Account</h4>
            <ul className="footer-links">
              <li><Link to="/login">Sign In</Link></li>
              <li><Link to="/register">Register</Link></li>
              <li><Link to="/dashboard">Member Portal</Link></li>
            </ul>
          </div>
          <div className="footer-col">
            <h4>Connect</h4>
            <ul className="footer-links">
              <li><a href="https://www.facebook.com/radicalledcalapan" target="_blank" rel="noopener noreferrer">Facebook</a></li>
              <li><a href="https://www.instagram.com/lifegencalapan" target="_blank" rel="noopener noreferrer">Instagram</a></li>
              <li><a href="https://www.tiktok.com/@life.generation7" target="_blank" rel="noopener noreferrer">TikTok</a></li>
            </ul>
          </div>
        </div>
        <div className="footer-bottom">
          <span>© {new Date().getFullYear()} Life Generation Minsu Christian Community. All rights reserved.</span>
          <span>Built with faith ✦</span>
        </div>
      </footer>
    </div>
  );
}
