import * as api from "@/app/_utils/api.js";

import axios from "axios";
import get from "lodash/get.js";

export const getRecommendedRate = async () => {
  const request = await api.createApiRequestInstance({
    path: "/recommandation/rate",
    method: "GET",
  });
  const response = await axios(request);

  const recommendedRate = get(response, "data.recommendedRate");
  
  return { recommendedRate };
};