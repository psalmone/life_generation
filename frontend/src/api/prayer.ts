import api from "./client";

export const getPrayer = async () => { const r = await api.get("/prayer"); return r.data.requests; };
export const submitPrayer = async (d: { title: string; body: string; visibility: string }) => { const r = await api.post("/prayer/submit", d); return r.data; };
export const markAnswered = async (request_id: number) => { const r = await api.post("/prayer/mark-answered", { request_id }); return r.data; };
export const deletePrayer = async (request_id: number) => { const r = await api.post("/prayer/delete", { request_id }); return r.data; };
