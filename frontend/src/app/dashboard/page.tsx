"use client";

import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { Building2, Users, Wrench, DollarSign } from "lucide-react";
import { Sidebar } from "@/components/layout/Sidebar";
import { Topbar } from "@/components/layout/Topbar";
import { StatCard } from "@/components/dashboard/StatCard";
import { api } from "@/lib/api";

interface DashboardSummary {
  total_branches: number;
  total_users: number;
  company: { name: string } | null;
}

async function fetchSummary(): Promise<DashboardSummary> {
  const { data } = await api.get("/dashboard/summary");
  return data;
}

export default function DashboardPage() {
  const { t } = useTranslation();
  const { data, isLoading } = useQuery({ queryKey: ["dashboard-summary"], queryFn: fetchSummary });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-secondary-900">
      <Sidebar activeHref="/dashboard" />
      <main className="md:ml-64 p-6">
        <Topbar />

        <h1 className="mb-1 text-xl font-semibold text-secondary-900 dark:text-white">
          {t("dashboard.welcome")}{data?.company ? `, ${data.company.name}` : ""}
        </h1>
        <p className="mb-6 text-sm text-gray-500 dark:text-gray-400">{t("app.tagline")}</p>

        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <StatCard
            label={t("dashboard.totalBranches")}
            value={isLoading ? "…" : data?.total_branches ?? 0}
            icon={Building2}
            accent="primary"
          />
          <StatCard
            label={t("dashboard.totalUsers")}
            value={isLoading ? "…" : data?.total_users ?? 0}
            icon={Users}
            accent="accent"
          />
          <StatCard
            label={t("dashboard.activeJobs")}
            value="—"
            icon={Wrench}
            trend="Phase 1 (Workshop module) coming next"
            accent="secondary"
          />
          <StatCard
            label={t("dashboard.revenueToday")}
            value="—"
            icon={DollarSign}
            trend="Phase 3 (Accounting module)"
            accent="primary"
          />
        </div>
      </main>
    </div>
  );
}
