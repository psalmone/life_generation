import api from "./client";

export const getDGroups = async () => { const r = await api.get("/dgroups"); return r.data.dgroups; };
export const joinDGroup = async (dgroup_id: number) => { const r = await api.post("/dgroups/join", { dgroup_id }); return r.data; };
export const leaveDGroup = async () => { const r = await api.post("/dgroups/leave", {}); return r.data; };
export const createDGroup = async (form: FormData) => { const r = await api.post("/dgroups/create", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const updateDGroup = async (form: FormData) => { const r = await api.post("/dgroups/update", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const deleteDGroup = async (dgroup_id: number) => { const r = await api.post("/dgroups/delete", { dgroup_id }); return r.data; };
