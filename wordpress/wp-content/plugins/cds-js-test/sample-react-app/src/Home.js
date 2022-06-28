const Home = () => {
  const { __ } = wp.i18n;
  return <><div>{__('REACT APP wp element', 'cds-js-test' )} - {__('js-html-react', 'cds-js-test')}!!</div> {__('more', 'cds-js-test')}!!{__('and more', 'cds-js-test')}</>;
};

export default Home;
