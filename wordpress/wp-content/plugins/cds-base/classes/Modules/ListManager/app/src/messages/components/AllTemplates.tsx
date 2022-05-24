import { ListTemplates } from "./ListTemplates";

export const AllTemplates = () => {
    return (
        <>
            <ListTemplates perPage={2} pageNav={true} />
        </>
    )
}

export default AllTemplates;