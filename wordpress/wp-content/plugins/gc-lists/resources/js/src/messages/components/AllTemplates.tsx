import { ListTemplates } from "./ListTemplates";

export const AllTemplates = () => {
    return (
        <>
            <ListTemplates perPage={10} pageNav={true} />
        </>
    )
}

export default AllTemplates;