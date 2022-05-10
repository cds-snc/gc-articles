import { Routes, Route } from "react-router-dom";
import { Main } from "./components/Main"
const MessagesApp = () => {
    return (
        <Routes>
            <Route path="/" element={<Main />} />
        </Routes>
    )
}

export default MessagesApp;