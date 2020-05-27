import { registerBlockType } from "@wordpress/blocks";
import { InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, PanelRow } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

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
      const { attributes: {content}, setAttributes, className, isSelected} = props;
  
      // Función para guardar el atributo content
      const handlerOnChangeInput = (newContent) => {
          setAttributes( {content: newContent})
      }
  
      return <>
          <InspectorControls>
              <PanelBody // Primer panel en la sidebar
                  title="Modificar texto del Bloque Básico"
                  initialOpen={ false }
              >
                  <PanelRow>
                      <TextControl
                          label="Complete el campo" // Indicaciones del campo
                          value={ content } // Asignación del atributo correspondiente
                          onChange={ handlerOnChangeInput } // Asignación de función para gestionar el evento OnChange
                      />
                  </PanelRow>
              </PanelBody>
          </InspectorControls>
          <ServerSideRender // Renderizado de bloque dinámico
              block="pg/basic" // Nombre del bloque
              attributes={ props.attributes } // Se envían todos los atributos
          />
      </>
    },
    save: (props) => null
  }
);