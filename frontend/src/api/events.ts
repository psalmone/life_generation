import api from "./client";

export const getEvents = async () => { const r = await api.get("/events"); return r.data.events; };
export const rsvpEvent = async (event_id: number) => { const r = await api.post("/events/rsvp", { event_id }); return r.data; };
export const cancelRsvp = async (event_id: number) => { const r = await api.post("/events/cancel-rsvp", { event_id }); return r.data; };
export const createEvent = async (form: FormData) => { const r = await api.post("/events/create", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const updateEvent = async (form: FormData) => { const r = await api.post("/events/update", form, { headers: { "Content-Type": "multipart/form-data" } }); return r.data; };
export const deleteEvent = async (event_id: number) => { const r = await api.post("/events/delete", { event_id }); return r.data; };
export const checkIn = async (event_id: number, member_id: string) => { const r = await api.post("/events/check-in", { event_id, member_id }); return r.data; };
