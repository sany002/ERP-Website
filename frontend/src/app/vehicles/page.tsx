"use client";

import { useState } from "react";
import { useTranslation } from "react-i18next";
import { useQuery } from "@tanstack/react-query";
import { Plus, Search, Car } from "lucide-react";
import { Sidebar } from "@/components/layout/Sidebar";
import { Topbar } from "@/components/layout/Topbar";
import { api } from "@/lib/api";

interface Vehicle {
  id: number;
  registration_number: string;
  vehicle_type: string;
  make: string | null;
  model: string | null;
  color: string | null;
  current_mileage: number;
  customer: { name: string; phone: string | null };
}

async function fetchVehicles(search: string) {
  const { data } = await api.get("/vehicles", { params: { search } });
  return data.data as Vehicle[];
}

export default function VehiclesPage() {
  const { t } = useTranslation();
  const [search, setSearch] = useState("");
  const { data: vehicles, isLoading } = useQuery({
    queryKey: ["vehicles", search],
    queryFn: () => fetchVehicles(search),
  });

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-secondary-900">
      <Sidebar activeHref="/vehicles" />
      <main className="md:ml-64 p-6">
        <Topbar />

        <div className="mb-6 flex items-center justify-between">
          <div>
            <h1 className="text-xl font-semibold text-secondary-900 dark:text-white">{t("nav.vehicles")}</h1>
            <p className="text-sm text-gray-500 dark:text-gray-400">Registered vehicles across all customers</p>
          </div>
          <button className="btn-primary gap-2">
            <Plus size={16} /> Add Vehicle
          </button>
        </div>

        <div className="card mb-4 flex items-center gap-2 py-2.5">
          <Search size={16} className="text-gray-400" />
          <input
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by reg. number, make, or model..."
            className="w-full bg-transparent text-sm outline-none"
          />
        </div>

        <div className="card overflow-x-auto p-0">
          <table className="w-full text-sm">
            <thead className="border-b border-gray-100 text-left text-xs uppercase text-gray-500 dark:border-secondary-700 dark:text-gray-400">
              <tr>
                <th className="px-5 py-3">Registration</th>
                <th className="px-5 py-3">Vehicle</th>
                <th className="px-5 py-3">Owner</th>
                <th className="px-5 py-3">Mileage</th>
                <th className="px-5 py-3">Type</th>
              </tr>
            </thead>
            <tbody>
              {isLoading && (
                <tr><td colSpan={5} className="px-5 py-8 text-center text-gray-400">{t("common.loading")}</td></tr>
              )}
              {!isLoading && vehicles?.length === 0 && (
                <tr><td colSpan={5} className="px-5 py-8 text-center text-gray-400">{t("common.noData")}</td></tr>
              )}
              {vehicles?.map((v) => (
                <tr key={v.id} className="border-b border-gray-50 last:border-0 hover:bg-gray-50 dark:border-secondary-700/50 dark:hover:bg-secondary-700/30">
                  <td className="px-5 py-3 font-medium text-secondary-900 dark:text-white">
                    <div className="flex items-center gap-2">
                      <Car size={15} className="text-primary" /> {v.registration_number}
                    </div>
                  </td>
                  <td className="px-5 py-3 text-gray-600 dark:text-gray-300">
                    {[v.make, v.model, v.color].filter(Boolean).join(" · ") || "—"}
                  </td>
                  <td className="px-5 py-3 text-gray-600 dark:text-gray-300">
                    {v.customer.name}
                    {v.customer.phone && <span className="ml-1 text-xs text-gray-400">({v.customer.phone})</span>}
                  </td>
                  <td className="px-5 py-3 text-gray-600 dark:text-gray-300">{v.current_mileage.toLocaleString()} km</td>
                  <td className="px-5 py-3 capitalize text-gray-600 dark:text-gray-300">{v.vehicle_type}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </main>
    </div>
  );
}
