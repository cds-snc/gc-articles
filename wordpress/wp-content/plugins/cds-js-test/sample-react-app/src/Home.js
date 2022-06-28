import React from "react";

const Home = () => {
  const { __ } = wp.i18n;
  return <div>{__('REACT APP', 'cds-js-test' )} {__('js-html-react', 'cds-js-test')}!!</div>;
};

export default Home;
