import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    host: "0.0.0.0",
    port: "80",
    allowedHosts: [
      "all",
      "84b5e91d-ed35-4cbe-8eac-9578d902fb29-00-2z21rqvxwzpfa.spock.replit.dev",
      "static.9.49.235.167.clients.your-server.de",
    ],
  },
  plugins: [react()],
});
