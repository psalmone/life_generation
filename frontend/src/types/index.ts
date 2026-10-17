export interface User {
  id: number;
  username: string;
  email: string;
  role: "user" | "moderator" | "admin";
  member_id: string | null;
  full_name: string;
  avatar: string | null;
  age?: number | null;
  address?: string | null;
  dgroup_id?: number | null;
  ministry_id?: number | null;
  joined_at?: string | null;
  is_active?: number;
}

export interface Member {
  id?: number;
  user_id?: number;
  member_id: string;
  full_name: string;
  avatar: string | null;
  age: number | null;
  address: string | null;
  email: string;
  is_active: number;
  joined_at: string;
  dgroup_id?: number | null;
  ministry_id?: number | null;
  dgroup_name?: string;
  ministry_name?: string;
  username?: string;
  role?: string;
}

export interface DGroup {
  id: number;
  name: string;
  leader_name: string;
  schedule: string;
  location: string;
  description?: string;
  image?: string | null;
  member_count?: number;
  created_at?: string;
}

export interface Ministry {
  id: number;
  name: string;
  icon: string;
  description?: string;
  image?: string | null;
  is_active: number;
  application_status?: string | null;
  has_applied?: boolean;
  member_count?: number;
  pending_apps_count?: number;
}

export interface Event {
  id: number;
  title: string;
  description?: string;
  banner_image?: string | null;
  location: string;
  event_date: string;
  event_time: string;
  capacity?: number | null;
  is_active: number;
  rsvp_count?: number;
  checked_in_count?: number;
}

export interface PrayerRequest {
  id: number;
  user_id: number;
  member_name: string;
  title: string;
  body: string;
  visibility: "public" | "private";
  status: "active" | "answered";
  created_at: string;
}

export interface MinistryApplication {
  id: number;
  user_id: number;
  ministry_id: number;
  ministry_name: string;
  icon?: string;
  member_name: string;
  member_id?: string;
  member_email?: string;
  message?: string;
  status: "pending" | "approved" | "rejected";
  created_at: string;
}

export interface Tokens {
  access_token: string;
  refresh_token: string;
  expires_in: number;
  token_type: string;
}

export interface DashboardData {
  user: User;
  member: Member;
  current_dgroup: DGroup | null;
  all_dgroups: DGroup[];
  current_ministry: Ministry | null;
  ministries: Ministry[];
  user_apps: MinistryApplication[];
  upcoming_events: Event[];
  user_rsvps: { event_id: number; rsvp_status: string; checked_in: number }[];
  prayer_requests: PrayerRequest[];
  // admin
  all_members?: Member[];
  total_members?: number;
  active_members?: number;
  all_dgroups_admin?: DGroup[];
  all_ministries_admin?: Ministry[];
  all_events_admin?: Event[];
  all_ministry_apps?: MinistryApplication[];
}
