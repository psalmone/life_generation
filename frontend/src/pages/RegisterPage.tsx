import { useState, FormEvent } from "react";
import { Link, useNavigate } from "react-router-dom";
import { GoogleLogin } from "@react-oauth/google";
import { register, loginWithGoogle } from "../api/auth";
import { useAuth } from "../context/AuthContext";

export default function RegisterPage() {
  const { login: authLogin } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ full_name: "", email: "", address: "", age: "", password: "", password_confirm: "" });
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [loading, setLoading] = useState(false);

  const update = (k: string) => (e: any) => setForm(p => ({ ...p, [k]: e.target.value }));

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault(); setError(""); setLoading(true);
    try {
      const data = await register(form);
      setSuccess(`Membership created! Your Member ID is ${data.member_id}. Redirecting to sign in…`);
      setTimeout(() => navigate("/login"), 3000);
    } catch (err: any) {
      setError(err.response?.data?.error || "Registration failed. Please try again.");
    } finally { setLoading(false); }
  };

  const handleGoogleSuccess = async (credentialResponse: any) => {
    if (!credentialResponse.credential) {
      setError("No credential received from Google.");
      return;
    }
    setError(""); setLoading(true);
    try {
      const data = await loginWithGoogle(credentialResponse.credential);
      authLogin(data.user, data.tokens.access_token, data.tokens.refresh_token);
      navigate("/dashboard");
    } catch (err: any) {
      setError(err.response?.data?.error || "Google registration failed. Please try again.");
    } finally { setLoading(false); }
  };

  return (
    <div style={{
      minHeight: "calc(100vh - 65px)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      padding: "2.5rem 1.25rem",
      position: "relative",
      background: `
        radial-gradient(ellipse 90% 75% at 15% 20%, rgba(37, 99, 235, 0.55) 0%, transparent 60%),
        radial-gradient(ellipse 90% 75% at 85% 80%, rgba(234, 88, 12, 0.6) 0%, transparent 60%),
        radial-gradient(ellipse 70% 60% at 80% 20%, rgba(249, 115, 22, 0.45) 0%, transparent 55%),
        radial-gradient(ellipse 70% 60% at 20% 80%, rgba(56, 189, 248, 0.45) 0%, transparent 55%),
        radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.25) 0%, transparent 55%),
        linear-gradient(135deg, #3b82f6 0%, #818cf8 32%, #fb923c 70%, #ea580c 100%)
      `,
    }}>

      {/* Floating Center Card (Favor Church inspired) */}
      <div style={{
        width: "100%",
        maxWidth: 500,
        background: "#ffffff",
        borderRadius: 20,
        padding: "2.75rem 2.25rem 2.25rem",
        boxShadow: "0 24px 60px -12px rgba(15, 23, 42, 0.16), 0 8px 24px -6px rgba(15, 23, 42, 0.08)",
        border: "1px solid rgba(255, 255, 255, 0.9)",
        position: "relative",
        zIndex: 2,
      }}>
        {/* Diamond / Round Logo Container */}
        <div style={{
          width: 52,
          height: 52,
          borderRadius: 14,
          background: "#ffffff",
          border: "1px solid #f1f5f9",
          boxShadow: "0 4px 16px rgba(249, 115, 22, 0.15)",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          margin: "0 auto 1.25rem",
          padding: 6,
          overflow: "hidden",
        }}>
          <img
            src="/assets/images/lifegen-logo.png"
            alt="Life Generation"
            style={{ width: "100%", height: "100%", objectFit: "contain", display: "block" }}
          />
        </div>

        {/* Headings */}
        <div style={{ textAlign: "center", marginBottom: "1.75rem" }}>
          <h1 style={{
            fontSize: "1.75rem",
            fontWeight: 800,
            color: "#0f172a",
            letterSpacing: "-0.025em",
            marginBottom: "0.4rem",
            lineHeight: 1.2,
          }}>
            Join the Family
          </h1>
          <p style={{
            fontSize: "0.88rem",
            color: "#64748b",
            lineHeight: 1.5,
            margin: 0,
          }}>
            Create your Life Generation account to connect with our community.
          </p>
        </div>

        {error && (
          <div style={{
            padding: "0.75rem 1rem",
            background: "#fef2f2",
            border: "1px solid #fee2e2",
            borderRadius: 10,
            color: "#b91c1c",
            fontSize: "0.85rem",
            fontWeight: 600,
            marginBottom: "1.25rem",
            display: "flex",
            alignItems: "center",
            gap: 8,
          }}>
            <span>⚠</span>
            <span>{error}</span>
          </div>
        )}

        {success && (
          <div style={{
            padding: "0.75rem 1rem",
            background: "#f0fdf4",
            border: "1px solid #dcfce7",
            borderRadius: 10,
            color: "#15803d",
            fontSize: "0.85rem",
            fontWeight: 600,
            marginBottom: "1.25rem",
            display: "flex",
            alignItems: "center",
            gap: 8,
          }}>
            <span>🎉</span>
            <span>{success}</span>
          </div>
        )}

        {/* Google Continue Button */}
        <div style={{ display: "flex", justifyContent: "center", width: "100%", marginBottom: "1.25rem" }}>
          <GoogleLogin
            onSuccess={handleGoogleSuccess}
            onError={() => setError("Google Sign-Up was cancelled or failed.")}
            theme="outline"
            size="large"
            text="continue_with"
            shape="rectangular"
            width="360"
          />
        </div>

        {/* Clean Divider */}
        <div style={{ display: "flex", alignItems: "center", gap: 14, margin: "1.5rem 0" }}>
          <div style={{ flex: 1, height: 1, background: "#e2e8f0" }} />
          <span style={{ fontSize: "0.72rem", fontWeight: 700, color: "#94a3b8", letterSpacing: "0.08em" }}>
            OR REGISTER WITH EMAIL
          </span>
          <div style={{ flex: 1, height: 1, background: "#e2e8f0" }} />
        </div>

        {/* Registration Form */}
        <form onSubmit={handleSubmit} style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
          <div>
            <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
              Full Name *
            </label>
            <input
              id="reg-full-name"
              placeholder="e.g. John Doe"
              value={form.full_name}
              onChange={update("full_name")}
              required
              autoComplete="name"
              style={{
                width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
              }}
              onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
              onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
            />
          </div>

          <div>
            <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
              Email Address *
            </label>
            <input
              id="reg-email"
              type="email"
              placeholder="you@example.com"
              value={form.email}
              onChange={update("email")}
              required
              autoComplete="email"
              style={{
                width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
              }}
              onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
              onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
            />
          </div>

          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "0.75rem" }}>
            <div>
              <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
                Age
              </label>
              <input
                id="reg-age"
                type="number"
                placeholder="21"
                min="5"
                max="120"
                value={form.age}
                onChange={update("age")}
                style={{
                  width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                  border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
                }}
                onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
                onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
              />
            </div>
            <div>
              <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
                Address / City
              </label>
              <input
                id="reg-address"
                placeholder="Calapan City"
                value={form.address}
                onChange={update("address")}
                style={{
                  width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                  border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
                }}
                onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
                onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
              />
            </div>
          </div>

          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "0.75rem" }}>
            <div>
              <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
                Password *
              </label>
              <input
                id="reg-password"
                type="password"
                placeholder="••••••••"
                value={form.password}
                onChange={update("password")}
                required
                style={{
                  width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                  border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
                }}
                onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
                onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
              />
            </div>
            <div>
              <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 700, color: "#334155", marginBottom: 6 }}>
                Confirm *
              </label>
              <input
                id="reg-password-confirm"
                type="password"
                placeholder="••••••••"
                value={form.password_confirm}
                onChange={update("password_confirm")}
                required
                style={{
                  width: "100%", height: 44, padding: "0 14px", borderRadius: 8,
                  border: "1.5px solid #cbd5e1", fontSize: "0.92rem", color: "#0f172a", background: "#ffffff", outline: "none", boxSizing: "border-box"
                }}
                onFocus={e => e.currentTarget.style.borderColor = "#3b82f6"}
                onBlur={e => e.currentTarget.style.borderColor = "#cbd5e1"}
              />
            </div>
          </div>

          {/* Solid Dark Button (Favor Church style) */}
          <button
            id="reg-submit"
            type="submit"
            disabled={loading}
            style={{
              width: "100%",
              height: 48,
              borderRadius: 8,
              background: "#000000",
              color: "#ffffff",
              fontWeight: 700,
              fontSize: "0.95rem",
              border: "none",
              cursor: loading ? "not-allowed" : "pointer",
              marginTop: "0.5rem",
              boxShadow: "0 4px 14px rgba(0, 0, 0, 0.2)",
              transition: "all 0.2s ease",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              opacity: loading ? 0.7 : 1,
            }}
            onMouseEnter={e => !loading && (e.currentTarget.style.background = "#1e293b")}
            onMouseLeave={e => !loading && (e.currentTarget.style.background = "#000000")}
          >
            {loading ? "Creating account..." : "Complete Registration"}
          </button>
        </form>

        {/* Footer */}
        <p style={{ textAlign: "center", marginTop: "1.75rem", fontSize: "0.85rem", color: "#64748b" }}>
          Already have an account?{" "}
          <Link
            to="/login"
            style={{ color: "#2563eb", fontWeight: 700, textDecoration: "none" }}
          >
            Sign in here
          </Link>
        </p>
      </div>
    </div>
  );
}
