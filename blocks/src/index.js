import { registerBlockType } from "@wordpress/blocks";
import { TextControl, PanelBody, PanelRow, Button } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { InspectorControls, MediaUpload } from '@wordpress/block-editor'; 

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
      },
      mediaURL: {
        type: 'string' // Definimos el tipo de dato que debe esperar el componente. En este caso es un texto (URL de la imagen), por eso lo declaramos como string.
      },
      mediaAlt: {
          type: 'string' // Definimos el tipo de dato que debe esperar el componente. En este caso es un texto (Texto Alternativo de la imagen), por eso lo declaramos como string.
      }
    },
    edit: (props) => {
      const { attributes: {content}, setAttributes, className, isSelected} = props;
      const handlerOnSelectMediaUpload = (image) => {
        setAttributes({
            mediaURL: image.sizes.full.url, // El objeto image cuenta con todas las propiedades de los archivos de la Media Library de WordPress, entre ellas los diferentes tamaños
            mediaAlt: image.alt // También cuenta con el Texto Alternativo definido en la Media Library para cada archivo
        })
      }
      // Función para guardar el atributo content
      const handlerOnChangeInput = (newContent) => {
          setAttributes( {content: newContent})
      }
  
      return <>
            <InspectorControls>
              <PanelBody
                  title="Modificar texto del Bloque Básico"
                  initialOpen={ true }
              >
                  <PanelRow>
                      <TextControl
                          label="Complete el campo" // Indicaciones del campo
                          value={ content } // Asignación del atributo correspondiente
                          onChange={ handlerOnChangeInput } // Asignación de función para gestionar el evento OnChange
                      />
                  </PanelRow>
              </PanelBody>
              <PanelBody
                  title="Selecciona una imagen"
                  initialOpen={ true }
              >
                  <PanelRow>
                      <MediaUpload 
                          onSelect={ handlerOnSelectMediaUpload } // Asignación de función para gestionar el evento OnSelect
                          type="image" // Limita los tipos de archivos que se pueden seleccionar
                          // Se envía el evento open y el renderizado del elemento que desencadenará la apertura de la librería, en este caso un botón
                          render={ ({open}) => {
                              //Agregamos las clases de los botones de WordPress habituales para que mantenga el estilo dentro del editor
                              return <Button className="components-icon-button image-block-btn is-button is-default is-large" onClick={open}>Elegir una imagen</Button>;
                          }}
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