import { registerBlockType } from "@wordpress/blocks";
import { TextControl } from "@wordpress/components";

registerBlockType('pg/basic', 
  {
    title: "Basic Block",
    description: "Este es el primer bloque",
    icon: "smiley",
    category: "layout",
    attributes: {
      content: {
        type: "string",
        default: "Hello World"
      }
    },
    edit: (props) => {
      const { attributes: { content }, setAttributes, className, isSelected } = props;
      const handlerOnChangeInput = (newContent) => {
        setAttributes({content: newContent});
      }
      return <TextControl
              label= "complete el campo"
              value = {content}
              onChange = {handlerOnChangeInput}
            />
    },
    save: (props) => null
  }
);