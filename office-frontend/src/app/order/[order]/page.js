"use client";
import { useState } from "react";
import { useRouter } from "next/navigation";
import ButtonTest from "@/components/buttonTest";
import { createComplaintReturn } from "@/app/_api/order/order.api";
import get from "lodash/get.js";
import ArrowCircleLeftIcon from "@mui/icons-material/ArrowCircleLeft";
import Link from "next/link";

export default function HoverRating({ params: order }) {
  const router = useRouter();
  const [typeAction, setType] = useState("Retun");
  const [description, setDescription] = useState("");
  const idOrder = order.order;

  const handleOrder = async (type, description, orderId) => {
    if (!type || !description || !orderId) {
      return;
    }

    const response = await createComplaintReturn(type, description, orderId);
    const errorCodeResponse = get(response, "code");
    if (errorCodeResponse === 401) {
      return;
    }
    router.push("/order");
  };
  const handleButtonClick = () => {
    const type = typeAction;
    const orderId = idOrder;
    handleOrder(type, description, orderId);
  };

  return (
    <main className="flex min-h-screen flex-col items-center p-24 bg-white">
      <div style={{ display: "flex", alignItems: "center" }}>
        <Link href="/order" className="text-[#22577a] mb-[60px] mr-[100px]">
          <ArrowCircleLeftIcon />
        </Link>
        <h1 className="text-[#22577a] font-['Arial'] text-4xl leading-[normal] mb-[60px]">
          Retour / réclamation
        </h1>
      </div>

      <form>
        <div className="flex flex-col items-start">
          {/* Type Demande */}
          <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
            <div className="text-black font-['Roboto'] leading-[normal]">
              Spécifier le type de votre demande
            </div>
            <div className="flex items-start">
              <select
                value={typeAction}
                onChange={(e) => setType(e.target.value)}
                className="w-[500px] h-[40px] text-black bg-white border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600"
              >
                <option value="Retun">Un retour</option>
                <option value="Complaint">Une reclamation</option>
              </select>
            </div>
          </div>

          {/* Id Commande */}
          <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
            <div className="text-black leading-[normal]">N° Commande</div>
            <div className="flex items-start">
              <input
                type="text"
                value={`ORD${idOrder}`}
                className="w-[500px] h-[40px] p-2 text-gray-500 bg-white border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600"
                disabled
              />
            </div>
          </div>

          {/* Champ de texte long pour le message */}
          <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
            <div className="text-black font-['Roboto'] leading-[normal]">
              Votre Message
            </div>
            <textarea
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              className="w-[100%] h-[120px] text-black bg-white border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600 resize-none"
            ></textarea>
          </div>

          <ButtonTest buttonText="Envoyer" onClick={handleButtonClick} />
        </div>
      </form>
    </main>
  );
}
