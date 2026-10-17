import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// Backend = http://lifegeneration.test (served from backend/public/)
// Frontend = http://localhost:5173      (this Vite dev server)

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    proxy: {
      // All /api/* calls are forwarded to LavaLust PHP backend
      "/api": {
        target: "http://lifegeneration.test",
        changeOrigin: true,
        secure: false,
      },
      // Uploads (avatars, event banners, D-group photos, etc.) from backend
      "/uploads": {
        target: "http://lifegeneration.test",
        changeOrigin: true,
        secure: false,
      },
      // Fallback: any /assets/* not in frontend/public proxied from backend
      "/assets": {
        target: "http://lifegeneration.test",
        changeOrigin: true,
        secure: false,
      },
    },
  },
});

