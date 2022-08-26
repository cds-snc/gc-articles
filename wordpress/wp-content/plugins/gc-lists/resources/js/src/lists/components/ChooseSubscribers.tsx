/**
 * External dependencies
 */
import styled from 'styled-components';
import { useNavigate } from 'react-router-dom';
import { __ } from "@wordpress/i18n";

 /**
  * Internal dependencies
  */
import { useService } from "../../store";
import { Back, StyledLink } from "../../common";
import { ListType } from "../../types";

const StyledDivider  = styled.div`
    border: 1px solid #cccccc;
    padding: 10px;
    margin-top: -1px; /* This is a hack so that the borders overlap */
    display: flex;

    > div {
        flex: 1;

        &:first-child {
            padding-right: 15px;
        }

        &:nth-child(2) {
            @media only screen and (max-width: 600px) {
                display: none;
            }
        }
    }

    figcaption {
        font-size: 14.5px;
        color: #646970;
    }

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
    const { listId, type } = useService();
    const titleType = type === ListType.PHONE ? 'text message' : 'email';
    const importType = type === ListType.PHONE ? 'text messages' : 'email addresses';

    return (
        <>
            <StyledLink to={`/lists`}>
                {/* Put the name of the list here */}
                <Back /> <span>{__("Edit list", "gc-lists")}</span>
            </StyledLink>
            <h1>{__(`Add ${titleType} subscribers`, "gc-lists")}</h1>
            <p>{__("Choose an option to add subscribers to this list. You can come back at any time to add more subscribers.", "gc-lists")}</p>

            <StyledDivider>
                <div>
                    <h2>{__("If you already have subscribers", "gc-lists")}</h2>
                    <p><strong>{__("Import existing subscribers", "gc-lists")}</strong></p>
                    <p>{__("Upload a spreadsheet of your subscribers in CSV format. It must have a column with", "gc-lists")} {importType}.</p>
                    <button
                        className="button button-primary"
                        type="button"
                        onClick={() => {
                            navigate(`/lists/${listId}/upload/${type}`, { replace: true });
                        }}
                    >
                        {__('Choose a file', 'gc-lists')}
                    </button>
                </div>
                <div>
                    {type === ListType.EMAIL &&
                        <figure>
                            <img src={process.env.PUBLIC_URL + "/email-upload-1.svg"} alt="" />
                            <figcaption>{__("Example of a spreadsheet of subscribers in CSV format with a column for emails.", "gc-lists")}</figcaption>
                        </figure>
                    }
                    {type === ListType.PHONE &&
                        <figure>
                            <img src={process.env.PUBLIC_URL + "/phone-upload-1.svg"} alt="" />
                            <figcaption>{__("Example of a spreadsheet of subscribers in CSV format with a column for phone numbers.", "gc-lists")}</figcaption>
                        </figure>
                    }
                </div>
            </StyledDivider>
            {type === ListType.EMAIL &&
                <StyledDivider>
                    <div>
                        <h2>{__("If you don’t have subscribers yet", "gc-lists")}</h2>
                        <p><strong>{__("Start collecting subscriber emails", "gc-lists")}</strong></p>
                        <p>
                            <a href={__("https://articles.alpha.canada.ca/knowledge-base-de-connaissances/setting-up-a-gc-notify-integration/", "gc-lists")} target="_blank">
                                {__("Set up a form", "gc-lists")}
                            </a> {__("to collect email addresses from subscribers. This will create a content block you can add to your pages.", "gc-lists")}
                        </p>
                    </div>
                    <div>
                        <figure>
                            <img src={process.env.PUBLIC_URL + "/email-upload-2.svg"} />
                            <figcaption>{__("Example of a form that can be set up to collect emails.", "gc-lists")}</figcaption>
                        </figure>
                    </div>
                </StyledDivider>
            }

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
 