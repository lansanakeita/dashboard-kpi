export default function Stars({ hovered, clicked }) {
    const color = clicked
        ? { stroke: "#22577A", fill: "#f0ff23" }
        : hovered
            ? { stroke: "#22577A", fill: "#f0ff23a2" }
            : { stroke: "#22577A", fill: "white" };
    if (clicked)
        return (
            <svg
                width={27}
                height={25}
                viewBox="0 0 27 25"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M13.2576 1.61804L15.813 9.48278L15.9252 9.82827H16.2885H24.558L17.8678 14.689L17.5739 14.9025L17.6862 15.248L20.2416 23.1127L13.5515 18.252L13.2576 18.0385L12.9637 18.252L6.27352 23.1127L8.82893 15.248L8.94119 14.9025L8.6473 14.689L1.95715 9.82827H10.2266H10.5899L10.7022 9.48278L13.2576 1.61804Z"
                    fill={color.fill}
                    stroke={color.stroke}
                />
            </svg>
        );

    return (
        <svg
            width={27}
            height={25}
            viewBox="0 0 27 25"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M13.2576 1.61804L15.813 9.48278L15.9252 9.82827H16.2885H24.558L17.8678 14.689L17.5739 14.9025L17.6862 15.248L20.2416 23.1127L13.5515 18.252L13.2576 18.0385L12.9637 18.252L6.27352 23.1127L8.82893 15.248L8.94119 14.9025L8.6473 14.689L1.95715 9.82827H10.2266H10.5899L10.7022 9.48278L13.2576 1.61804Z"
                fill="white"
                stroke="#22577A"
            />
        </svg>
    );
}
