export const getListType = (type: string) => {
  //@todo --- temporary... note we're using language here -> en=email, fr=phone 
  if (type === 'en') {
    return 'email';
  }
  return 'phone';
};
