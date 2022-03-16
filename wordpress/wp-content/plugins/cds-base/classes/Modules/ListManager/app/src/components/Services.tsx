import * as React from 'react';
import { useList } from "../store/ListContext";
import { Link } from "react-router-dom";
import styled from 'styled-components';

const StyledLink = styled.div`
   font-size:1.2rem;
   margin-bottom: 1rem;
`

export const Services = () => {
    const { state: { serviceData } } = useList();

    if(!serviceData || serviceData?.length <1){
        return  <><p>No services found.</p></>
    }

    return (
        <div>
            {serviceData && serviceData.map((service) => {
                return <div key={service.service_id}>
                    <StyledLink>
                        <Link to={{ pathname: `/service/${service.service_id}` }}>
                            {service.name}
                        </Link>
                    </StyledLink>
                </div>
            })}
        </div>
    )
}