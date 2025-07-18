import apiClient from "../../authClient";
interface User {
  success: boolean;
  name: string;
  email: string;
  token: string;
}
export const setUser = (userData: User) => {
  return {
    type: "SET_USER",
    payload: userData,
  };
};

export const loginUser = async (email: string, password: string) => {
  try {
    const response = await apiClient.post("/login", {
      email,
      password,
    },
   {withCredentials: true});

    const { token } = response.data;

    console.log("✅ Login successful:", response);

    return {
      type: "LOGIN_USER",
      payload: {
        success: response.data.success,
        name,
        email,
        password,
        token,
      },
    };
  } catch (error) {
    console.log(error);
    return { type: "LOGIN_FAIL", payload: "" };
  }
};

export const fetchCurrentUser = () => {
  return async (dispatch: any) => {
    try {
      const response = await apiClient.get("/me", {
        withCredentials: true,
      });

      const { user } = response.data;

      dispatch({
        type: "SET_USER",
        payload: user,
      });
    } catch (error) {
      console.error("Not authenticated", error);
      dispatch({ type: "LOGIN_FAIL", payload: "" });
    }
  };
};

export const checkToken = async () => {
  try {
    const token = localStorage.getItem("accessToken");

    if (!token) {
      throw new Error("Token not found");
    }

    const response = await apiClient.post(
      "/me",
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
        withCredentials: true, // only if your backend uses cookies too
      }
    );

    console.log("✅ /me raw response:", response.data);

    return response.data;
  } catch (error) {
    console.error("❌ Token verification failed:", error);
    return null;
  }
};



export type Action = ReturnType<typeof setUser>;
