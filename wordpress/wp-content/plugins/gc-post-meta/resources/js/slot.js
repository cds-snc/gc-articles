const { createSlotFill } = wp.components;
 
export const { Fill, Slot } = createSlotFill( 'GCPostMetaSlotFill' );
 
const GCPostMetaSlotFill = ( { children } ) => <Fill>{ children }</Fill>;

GCPostMetaSlotFill.Slot = Slot;
 
export default GCPostMetaSlotFill;