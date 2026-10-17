import api from "./client";
import type { User, Tokens } from "../types";

export const login = async (identifier: string, password: string) => {
  const res = await api.post("/auth/login", { identifier, password });
  return res.data as { user: User; tokens: Tokens; message: string };
};

export const loginWithGoogle = async (credential: string) => {
  const res = await api.post("/auth/google", { credential });
  return res.data as { user: User; tokens: Tokens; message: string };
};

export const register = async (data: {
  full_name: string; email: string; address: string;
  age: string; password: string; password_confirm: string;
}) => {
  const res = await api.post("/auth/register", data);
  return res.data as { message: string; member_id: string };
};

export const me = async () => {
  const res = await api.get("/auth/me");
  return res.data.user as User;
};

export const logout = async (refreshToken: string) => {
  await api.post("/auth/logout", { refresh_token: refreshToken });
};

export const getPublicHome = async () => {
  const res = await api.get("/public/home");
  return res.data;
};
