import api from "./client";

export const getMinistries = async () => { const r = await api.get("/ministries"); return r.data.ministries; };
export const applyMinistry = async (ministry_id: number, message: string) => { const r = await api.post("/ministries/apply", { ministry_id, message }); return r.data; };
export const cancelMinistry = async (ministry_id: number) => { const r = await api.post("/ministries/cancel", { ministry_id }); return r.data; };
export const updateApplication = async (app_id: number, status: string) => { const r = await api.post("/ministries/update-application", { app_id, status }); return r.data; };
export const deleteApplication = async (app_id: number) => { const r = await api.post("/ministries/delete-application", { app_id }); return r.data; };
export const createMinistry = async (form: FormData) => { const r = await api.post("/ministries/create", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const updateMinistry = async (form: FormData) => { const r = await api.post("/ministries/update", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const deleteMinistry = async (ministry_id: number) => { const r = await api.post("/ministries/delete", { ministry_id }); return r.data; };
