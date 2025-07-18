import { setAccessToken } from "../../authClient";
import type { Action } from "../actions/auth";

const initialState = {
  token: "",
};

export const useSession = (state = initialState, action: Action) => {
  switch (action.type) {
    case "LOGIN_USER":
      if(action.payload.token) {
        localStorage.setItem("accessToken", action.payload.token);
        setAccessToken(action.payload.token);
      }
      return {
        ...state,
        token: action.payload.token,
        
      };

    default:
      return state;
  }
};
