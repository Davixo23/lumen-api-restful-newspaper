<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;// usar para el create
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{   
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // lista de registros en orden
    public function index(){
        $categories = Category::select('id','title','alias','position')->orderBy('position','ASC')->get();

        //return response($categories,200);
        return $this->validResponse($categories);// delega a un manejador de excepciones
    }
    // leer registros
    public function read($id){
        $category = Category::findOrFail($id);// encontrar al buscado

        //return response($category,200);
        return $this->validResponse($category);//handler
    }
    // crear registros
    public function create(Request $request){
        //Lumen trabaja con reglas de validacion
        $rules=[
            'title'=> 'required|max:60|unique:categories',
            'position'=> 'required|min:1|integer',
            'published'=> 'required|boolean'

        ];// se definen las reglas de validacion campo por campo
        $this->validate($request,$rules);//se validan con los parametros
        $data= $request->all();//$request instancia todos los valores que se va a pasar a la base de datos.
        $data['alias']=Str::slug($data['title']);// devuelve todo junto espacios por guiones etc. para la url de la noticia
        $data['created_by']='system';
        $category = Category::create($data);// encontrar al buscado

        //return response($category,201);// codigo de creado
        return $this->successResponse($category,Response::HTTP_CREATED);//respuesta en JSON
    }
    // actualizar registros
    public function update($id,Request $request){
        // por idempotencia el update debe actualizar los datos y si tiene un nuevo id debe crear la nueva caregoria

        //Lumen trabaja con reglas de validacion
        $rules=[
            // excepcion para los valores unique
            'title'=> 'required|max:60|unique:categories,title,'.$id,
            'position'=> 'required|min:1|integer',
            'published'=> 'required|boolean'

        ];// se definen las reglas de validacion campo por campo
        $this->validate($request,$rules);//se validan con los parametros
        
        $data= $request->all();//$request instancia todos los valores que se va a pasar a la base de datos.
        $data['alias']=Str::slug($data['title']);// devuelve todo junto espacios por guiones etc. para la url de la noticia

        
        $category = Category::find($id);// principio de idempotencia 
        //$category = Category::findOrFail($id);// si no existe obtiene el error 404

        if(empty($category)){
            $category = new Category();

            $category->id=$id;
            $category->title=$data['title'];
            $category->alias=$data['alias'];
            $category->position=$data['position'];
            $category->published=$data['published'];
            $category->created_by='system';
            $category->save();

            //return response($category,201);//CREADO
            return $this->successResponse($category, Response::HTTP_CREATED);
        }
        else{
            $data['updated_by']='system';
            $category->fill($data);// cargar $data en category 
            $category->save();
            //return response($category,200);// codigo ok
            return $this->successResponse($category,Response::HTTP_OK);// manejo de Excepciones
        }
    }
    // patch actualizacion parcial
    public function patch($id,Request $request){
        // por idempotencia el update debe actualizar los datos y si tiene un nuevo id debe crear la nueva caregoria

        //Lumen trabaja con reglas de validacion
        $rules=[
            // excepcion patch se quita el required porque se actualizan parcialmente los datos no todos se actualizan a la vez
            'title'=> 'max:60|unique:categories,title,'.$id,
            'position'=> 'min:1|integer',
            'published'=> 'boolean'

        ];// se definen las reglas de validacion campo por campo
        $this->validate($request,$rules);//se validan con los parametros
        
        $data= $request->all();//$request instancia todos los valores que se va a pasar a la base de datos.
        

        $category = Category::findOrFail($id);// si no existe obtiene el error 404
        if(isset($data['title'])){// isset("variable") si tiene valor devuelve true 
            $data['alias']=Str::slug($data['title']);
        }
        
        $data['updated_by']='system';

        $category->fill($data);// cargar $data en category 
        $category->save();
        //return response($category,200);// codigo ok
        return $this->successResponse($category,Response::HTTP_OK);
    }
    // delete registros
    public function delete($id){
        $category = Category::findOrFail($id);
        $category->delete();
        //return response($category,200);
        return $this->successResponse($category,Response::HTTP_OK);
    }
    public function indexV2(){
        $categories = Category::select('id','title','alias','position','created_at')->orderBy('position','ASC')->get();

        //return response($categories,200);
        return $this->validResponse($categories);// delega a un manejador de excepciones
    }
}
