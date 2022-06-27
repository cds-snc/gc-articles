import create from "zustand";


// set base url from app level?
// const URL = `${baseUrl}/wp-json/list-manager`;

const useStore = create((set) => ({
  lists: [],
  fetch: async (serviceId: string) => {
    const response = await fetch(`/lists/${serviceId}`)
    set({ lists: await response.json() })
  },
}))

export default useStore;