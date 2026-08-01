"use client";

import { useState } from "react";
import { useTranslation } from "react-i18next";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { LogIn, LogOut } from "lucide-react";
import { Sidebar } from "@/components/layout/Sidebar";
import { Topbar } from "@/components/layout/Topbar";
import { api } from "@/lib/api";
import { cn } from "@/lib/utils";

interface GateLogEntry {
  id: number;
  direction: "in" | "out";
  registration_number: string | null;
  driver_name: string | null;
  logged_at: string;
  vehicle: { registration_number: string } | null;
  job_card: { job_number: string } | null;
  gate_operator: { name: string };
}

async function fetchLogs() {
  const { data } = await api.get("/gate-logs", { params: { per_page: 25 } });
  return data.data as GateLogEntry[];
}

export default function GateLogPage() {
  const { t } = useTranslation();
  const queryClient = useQueryClient();
  const { data: logs, isLoading } = useQuery({ queryKey: ["gate-logs"], queryFn: fetchLogs });

  const [direction, setDirection] = useState<"in" | "out">("in");
  const [regNumber, setRegNumber] = useState("");
  const [driverName, setDriverName] = useState("");

  const mutation = useMutation({
    mutationFn: () =>
      api.post("/gate-logs", {
        direction,
        registration_number: regNumber,
        driver_name: driverName || undefined,
        branch_id: 1, // TODO: derive from active branch selector once multi-branch UI ships
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["gate-logs"] });
      setRegNumber("");
      setDriverName("");
    },
  });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-secondary-900">
      <Sidebar />
      <main className="md:ml-64 p-6">
        <Topbar />

        <h1 className="mb-1 text-xl font-semibold text-secondary-900 dark:text-white">Gate Log</h1>
        <p className="mb-6 text-sm text-gray-500 dark:text-gray-400">Vehicle entry / exit tracking</p>

        <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <form
            onSubmit={(e) => { e.preventDefault(); mutation.mutate(); }}
            className="card space-y-4 lg:col-span-1"
          >
            <div className="flex overflow-hidden rounded-lg border border-gray-200 dark:border-secondary-700">
              <button
                type="button"
                onClick={() => setDirection("in")}
                className={cn("flex flex-1 items-center justify-center gap-1.5 py-2 text-sm font-medium",
                  direction === "in" ? "bg-accent text-white" : "text-gray-500")}
              >
                <LogIn size={15} /> Entry
              </button>
              <button
                type="button"
                onClick={() => setDirection("out")}
                className={cn("flex flex-1 items-center justify-center gap-1.5 py-2 text-sm font-medium",
                  direction === "out" ? "bg-secondary-700 text-white" : "text-gray-500")}
              >
                <LogOut size={15} /> Exit
              </button>
            </div>

            <div>
              <label className="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Registration Number</label>
              <input value={regNumber} onChange={(e) => setRegNumber(e.target.value)} required className="input-field" />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Driver Name (optional)</label>
              <input value={driverName} onChange={(e) => setDriverName(e.target.value)} className="input-field" />
            </div>

            <button type="submit" disabled={mutation.isPending} className="btn-primary w-full">
              {mutation.isPending ? t("common.loading") : `Log ${direction === "in" ? "Entry" : "Exit"}`}
            </button>
          </form>

          <div className="card overflow-x-auto p-0 lg:col-span-2">
            <table className="w-full text-sm">
              <thead className="border-b border-gray-100 text-left text-xs uppercase text-gray-500 dark:border-secondary-700 dark:text-gray-400">
                <tr>
                  <th className="px-5 py-3">Time</th>
                  <th className="px-5 py-3">Direction</th>
                  <th className="px-5 py-3">Vehicle</th>
                  <th className="px-5 py-3">Job</th>
                  <th className="px-5 py-3">Operator</th>
                </tr>
              </thead>
              <tbody>
                {isLoading && <tr><td colSpan={5} className="px-5 py-8 text-center text-gray-400">{t("common.loading")}</td></tr>}
                {logs?.map((log) => (
                  <tr key={log.id} className="border-b border-gray-50 last:border-0 dark:border-secondary-700/50">
                    <td className="px-5 py-3 text-gray-500 dark:text-gray-400">
                      {new Date(log.logged_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}
                    </td>
                    <td className="px-5 py-3">
                      <span className={cn("rounded-full px-2 py-0.5 text-[11px] font-medium capitalize",
                        log.direction === "in" ? "bg-accent/10 text-accent-600" : "bg-secondary/10 text-secondary-700")}>
                        {log.direction}
                      </span>
                    </td>
                    <td className="px-5 py-3 font-medium text-secondary-900 dark:text-white">
                      {log.vehicle?.registration_number ?? log.registration_number}
                    </td>
                    <td className="px-5 py-3 text-gray-600 dark:text-gray-300">{log.job_card?.job_number ?? "—"}</td>
                    <td className="px-5 py-3 text-gray-600 dark:text-gray-300">{log.gate_operator.name}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  );
}
