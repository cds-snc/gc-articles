/**
 * External dependencies
 */
import styled from 'styled-components';
import { useNavigate } from 'react-router-dom';
import { __ } from "@wordpress/i18n";

 /**
  * Internal dependencies
  */
import { Error } from "./Error";
import { useList } from "../../store";
import { Back, StyledLink } from "../../common";


const StyledDivider  = styled.div`
    border: 1px solid #cccccc;
    padding: 10px;
    margin-top: -1px; /* This is a hack so that the borders overlap */

    h2 {
        font-size: 1.4em;
        margin-top: 5px;
    }

    p {
        margin: 0;
    }

    button.button-primary {
        margin: 10px 0 !important;
    }
`

 export const ChooseSubscribers = () => {
    const navigate = useNavigate();
    const { state: { serviceData, lists } } = useList();

    console.log('serviceData', serviceData)
    console.log('lists', lists)

    //const { state: { lists } } = useList();
    // const { listId } = useService()

    if (!serviceData) {
        return <Error />;
    }

    return (
        <>
            <StyledLink to={`/lists`}>
                {/* Put the name of the list here */}
                <Back /> <span>{__("Edit list", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Add email subscribers", "gc-lists")}</h1>
            <p>{__("Choose an option to add subscribers to this list. You can come back at any time to add more subscribers.", "gc-lists")}</p>

            <StyledDivider>
                <h2>{__("If you have an existing list.", "gc-lists")}</h2>
                <p><strong>{__("Import subscribers", "gc-lists")}</strong></p>
                <p>{__("Upload a spreadsheet of your subscribers in CSV format. It must have a column with the email addresses.", "gc-lists")}</p>


                <button
                    className="button button-primary"
                    type="button"
                    onClick={() => {
                        console.log('clicked!')
                    }}
                >
                    {__('Import using a CSV file', 'gc-lists')}
                </button>
            </StyledDivider>
            <StyledDivider>
                <h2>{__("If you don’t have an existing list", "gc-lists")}</h2>
                <p><strong>{__("Start collecting subscriber emails", "gc-lists")}</strong></p>
                <p>{__("Set up a form to collect email addresses from subscribers. This will create a content block you can add to your pages.", "gc-lists")}</p>
            </StyledDivider>

            <br />
            <button
                className="button"
                type="button"
                onClick={() => navigate('/lists/')}
            >
                {__('Return to mailing lists', 'gc-lists')}
            </button>
        </>
    )
 }
 
 export default ChooseSubscribers;
 