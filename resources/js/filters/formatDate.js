import dayjs from "dayjs"
export default function (date) {
    return dayjs(date).format("DD.MM.YYYY HH:mm");
}
