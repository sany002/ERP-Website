import { createSlice, PayloadAction } from "@reduxjs/toolkit";

interface Role {
  slug: string;
  permissions: { name: string }[];
}

interface AuthUser {
  id: number;
  name: string;
  email: string;
  company: { id: number; name: string } | null;
  roles: Role[];
}

interface AuthState {
  user: AuthUser | null;
  token: string | null;
}

const initialState: AuthState = { user: null, token: null };

const authSlice = createSlice({
  name: "auth",
  initialState,
  reducers: {
    setCredentials(state, action: PayloadAction<{ user: AuthUser; token: string }>) {
      state.user = action.payload.user;
      state.token = action.payload.token;
    },
    clearCredentials(state) {
      state.user = null;
      state.token = null;
    },
  },
});

export const { setCredentials, clearCredentials } = authSlice.actions;
export default authSlice.reducer;
