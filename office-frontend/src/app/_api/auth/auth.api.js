import * as api from "@/app/_utils/api.js";

import axios from "axios";
import get from "lodash/get.js";

export const login = async (username, password) => {
  const request = await api.createApiRequestInstance({
    path: "/login",
    method: "POST",
    data: { username, password },
  });
  const response = await axios(request);

  const token = get(response, "data.token");
  localStorage.setItem("app_token", response.data.token);
  return { token };
};

export const logout = () => {
  localStorage.removeItem("token");
};
