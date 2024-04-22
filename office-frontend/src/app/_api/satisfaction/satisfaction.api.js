import * as api from "@/app/_utils/api.js";

import axios from "axios";
import get from "lodash/get.js";

export const getAverageRating = async () => {
  const request = await api.createApiRequestInstance({
    path: "/responses/get-average-rating",
    method: "GET",
  });
  const response = await axios(request);

  const averageRating = get(response, "data.averageRating");
  return { averageRating };
};