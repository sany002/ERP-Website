"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { useTranslation } from "react-i18next";
import { useDispatch } from "react-redux";
import { api } from "@/lib/api";
import { setCredentials } from "@/store/authSlice";

export default function LoginPage() {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const router = useRouter();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setLoading(true);
    try {
      const { data } = await api.post("/auth/login", { email, password });
      dispatch(setCredentials({ user: data.user, token: data.access_token }));
      router.push("/dashboard");
    } catch {
      setError(t("auth.invalidCredentials"));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-secondary-900 via-secondary-800 to-primary-700 px-4">
      <div className="glass-panel w-full max-w-sm p-8">
        <div className="mb-6 flex flex-col items-center gap-2">
          <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-xl font-bold text-white">
            S
          </div>
          <h1 className="text-lg font-semibold text-white">{t("app.name")}</h1>
          <p className="text-center text-xs text-gray-300">{t("app.tagline")}</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-xs font-medium text-gray-200">{t("auth.email")}</label>
            <input
              type="email"
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="input-field bg-white/90"
            />
          </div>
          <div>
            <label className="mb-1 block text-xs font-medium text-gray-200">{t("auth.password")}</label>
            <input
              type="password"
              required
              minLength={8}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="input-field bg-white/90"
            />
          </div>

          {error && <p className="text-xs text-red-400">{error}</p>}

          <button type="submit" disabled={loading} className="btn-primary w-full">
            {loading ? t("common.loading") : t("auth.signIn")}
          </button>
        </form>

        <p className="mt-6 text-center text-[11px] text-gray-400">{t("app.developedBy")}</p>
      </div>
    </div>
  );
}
