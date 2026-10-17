import api from "./client";
import type { DashboardData } from "../types";

export const getDashboard = async () => {
  const res = await api.get("/members/dashboard");
  return res.data as DashboardData;
};

export const updateProfile = async (data: {
  full_name: string; email: string; address: string; age: string;
}) => {
  const res = await api.post("/members/update-profile", data);
  return res.data;
};

export const uploadAvatar = async (file: File) => {
  const form = new FormData();
  form.append("avatar", file);
  const res = await api.post("/members/upload-avatar", form, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data;
};

export const removeAvatar = async () => {
  const res = await api.post("/members/remove-avatar", {});
  return res.data;
};

export const toggleMemberStatus = async (member_row_id: number) => {
  const res = await api.post("/members/toggle-status", { member_row_id });
  return res.data;
};

export const updateMemberRole = async (user_id: number, role: string) => {
  const res = await api.post("/members/update-role", { user_id, role });
  return res.data;
};
