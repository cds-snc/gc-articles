import useFetch from "use-http";
import { ConfirmActionLink } from "./ConfirmActionLink"

export const ResetActionLink = ({id = ''}:{id: string}) => {
    const { request, response } = useFetch({ data: [] })
    
    const deleteList = async ({id = ''}:{id: string}) => {
        await request.put(`/list/${id}/reset`)
    
        if (response.ok) {
            console.log(response)
        } 
    }

    return <ConfirmActionLink text={"reset"} isConfirmedHandler={ () => deleteList({id}) } />
}