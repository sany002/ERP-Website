import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: "class",
  content: [
    "./src/app/**/*.{ts,tsx}",
    "./src/components/**/*.{ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // Synex ERP brand palette
        primary: {
          DEFAULT: "#2563EB",
          50: "#EFF6FF",
          100: "#DBEAFE",
          500: "#2563EB",
          600: "#1D4ED8",
          700: "#1E40AF",
        },
        secondary: {
          DEFAULT: "#0F172A",
          700: "#334155",
          800: "#1E293B",
          900: "#0F172A",
        },
        accent: {
          DEFAULT: "#14B8A6",
          500: "#14B8A6",
          600: "#0D9488",
        },
        surface: {
          light: "#FFFFFF",
          dark: "#111827",
        },
      },
      backdropBlur: {
        glass: "12px",
      },
      boxShadow: {
        card: "0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.08)",
        glass: "0 8px 32px 0 rgba(15, 23, 42, 0.12)",
      },
      borderRadius: {
        card: "0.875rem",
      },
    },
  },
  plugins: [],
};

export default config;
