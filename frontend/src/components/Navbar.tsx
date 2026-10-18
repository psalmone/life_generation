import { Link, useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useState, useEffect } from "react";

export default function Navbar() {
  const { user, isAuthenticated, logout } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();
  const [theme, setTheme] = useState<"light"|"dark">(() => {
    try {
      const saved = localStorage.getItem("theme");
      if (saved) return saved as "light"|"dark";
      return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    } catch { return "light"; }
  });

  useEffect(() => {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
  }, [theme]);

  const active = (p: string) => location.pathname === p ? "nav-link active" : "nav-link";
  const handleLogout = () => { logout(); navigate("/login"); };
  const toggleTheme = () => setTheme(t => t === "dark" ? "light" : "dark");

  return (
    <nav className="navbar">
      <div className="nav-container">
        <Link to="/" className="nav-brand">
          <div className="nav-brand-icon" style={{ background: "#fff", border: "1px solid var(--card-border)", overflow: "hidden", padding: 2 }}>
            <img
              src="/assets/images/lifegen-logo.png"
              alt="Life Generation Logo"
              style={{ width: "100%", height: "100%", objectFit: "contain", display: "block" }}
            />
          </div>
          <div>
            <div className="nav-brand-name">Life Generation</div>
            <div className="nav-brand-sub">Christian Fellowship</div>
          </div>
        </Link>

        <div className="navbar-links">
          <Link to="/" className={active("/")}>Home</Link>
          {isAuthenticated && (
            <>
              <Link to="/events" className={active("/events")}>Events</Link>
              <Link to="/dgroups" className={active("/dgroups")}>D-Groups</Link>
              <Link to="/ministries" className={active("/ministries")}>Ministries</Link>
              <Link to="/prayer-wall" className={active("/prayer-wall")}>Prayer Wall</Link>
            </>
          )}
        </div>

        <div className="nav-actions">
          <button
            className="theme-toggle"
            onClick={toggleTheme}
            aria-label="Toggle dark/light mode"
            title="Toggle theme"
          >
            {theme === "dark" ? (
              <svg width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            ) : (
              <svg width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
              </svg>
            )}
          </button>

          {isAuthenticated ? (
            <>
              <Link
                to="/dashboard"
                className={`nav-user-pill ${location.pathname === "/dashboard" ? "active" : ""}`}
                title="Open Member Portal"
              >
                <div className="nav-user-avatar">
                  {user?.avatar ? (
                    <img src={`/${user.avatar}`} alt={user.full_name || user.username} />
                  ) : (
                    (user?.full_name?.[0] || user?.username?.[0] || "U").toUpperCase()
                  )}
                </div>
                <span className="nav-user-name">
                  {user?.full_name?.split(" ")[0] ?? user?.username}
                </span>
                <span className="nav-user-badge">Portal</span>
              </Link>
              <button className="btn btn-outline btn-sm" onClick={handleLogout}>Sign Out</button>
            </>
          ) : (
            <>
              <Link to="/login" className="btn btn-outline btn-sm">Sign In</Link>
              <Link to="/register" className="btn btn-primary btn-sm">Join Community</Link>
            </>
          )}
        </div>
      </div>
    </nav>
  );
}
