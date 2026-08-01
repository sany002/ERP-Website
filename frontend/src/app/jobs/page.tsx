"use client";

import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { Plus, Wrench, Clock } from "lucide-react";
import { Sidebar } from "@/components/layout/Sidebar";
import { Topbar } from "@/components/layout/Topbar";
import { api } from "@/lib/api";
import { cn } from "@/lib/utils";

interface JobCard {
  id: number;
  job_number: string;
  status: string;
  priority: string;
  vehicle: { registration_number: string; make: string | null; model: string | null };
  customer: { name: string };
  assigned_mechanic: { name: string } | null;
  bay: { name: string } | null;
  grand_total: string;
}

const columns = [
  { key: "pending", label: "Pending" },
  { key: "in_progress", label: "In Progress" },
  { key: "waiting_parts", label: "Waiting Parts" },
  { key: "quality_check", label: "Quality Check" },
  { key: "completed", label: "Completed" },
  { key: "delivered", label: "Delivered" },
];

const priorityColor: Record<string, string> = {
  urgent: "bg-red-100 text-red-700",
  high: "bg-orange-100 text-orange-700",
  normal: "bg-blue-100 text-blue-700",
  low: "bg-gray-100 text-gray-600",
};

async function fetchJobs() {
  const { data } = await api.get("/jobs", { params: { per_page: 100 } });
  return data.data as JobCard[];
}

export default function JobsPage() {
  const { t } = useTranslation();
  const { data: jobs, isLoading } = useQuery({ queryKey: ["jobs"], queryFn: fetchJobs });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-secondary-900">
      <Sidebar />
      <main className="md:ml-64 p-6">
        <Topbar />

        <div className="mb-6 flex items-center justify-between">
          <div>
            <h1 className="text-xl font-semibold text-secondary-900 dark:text-white">Job Cards</h1>
            <p className="text-sm text-gray-500 dark:text-gray-400">Workshop job workflow, bay to delivery</p>
          </div>
          <button className="btn-primary gap-2">
            <Plus size={16} /> New Job Card
          </button>
        </div>

        {isLoading ? (
          <p className="text-sm text-gray-400">{t("common.loading")}</p>
        ) : (
          <div className="flex gap-4 overflow-x-auto pb-4">
            {columns.map((col) => {
              const items = jobs?.filter((j) => j.status === col.key) ?? [];
              return (
                <div key={col.key} className="w-72 flex-shrink-0">
                  <div className="mb-2 flex items-center justify-between px-1">
                    <h3 className="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                      {col.label}
                    </h3>
                    <span className="rounded-full bg-gray-200 px-2 py-0.5 text-[11px] font-medium text-gray-600 dark:bg-secondary-700 dark:text-gray-300">
                      {items.length}
                    </span>
                  </div>

                  <div className="space-y-3">
                    {items.map((job) => (
                      <div key={job.id} className="card space-y-2 p-4">
                        <div className="flex items-center justify-between">
                          <span className="text-xs font-semibold text-primary">{job.job_number}</span>
                          <span className={cn("rounded-full px-2 py-0.5 text-[10px] font-medium capitalize", priorityColor[job.priority])}>
                            {job.priority}
                          </span>
                        </div>
                        <p className="text-sm font-medium text-secondary-900 dark:text-white">
                          {job.vehicle.registration_number}
                        </p>
                        <p className="text-xs text-gray-500 dark:text-gray-400">
                          {[job.vehicle.make, job.vehicle.model].filter(Boolean).join(" ")} · {job.customer.name}
                        </p>
                        <div className="flex items-center justify-between pt-1 text-xs text-gray-500 dark:text-gray-400">
                          <span className="flex items-center gap-1">
                            <Wrench size={12} /> {job.assigned_mechanic?.name ?? "Unassigned"}
                          </span>
                          {job.bay && <span className="flex items-center gap-1"><Clock size={12} /> {job.bay.name}</span>}
                        </div>
                      </div>
                    ))}
                    {items.length === 0 && (
                      <div className="rounded-card border border-dashed border-gray-200 p-4 text-center text-xs text-gray-400 dark:border-secondary-700">
                        {t("common.noData")}
                      </div>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </main>
    </div>
  );
}
