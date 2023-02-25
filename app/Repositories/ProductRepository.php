<?php

namespace App\Repositories;
use App\Models\Category;
use App\Models\Company;
use File;
use Auth;

class ProductRepository{
   
    public function store_in_company($company , $products){
      $company->products = $products;
      $company->save();
      return ;
    }

    public function get_by_company($company_id){
     $company = Company::find($company_id);
     if(!$company)
     return response()->json(["message" => "company not found"]);
     $products = $company->products;
     return response()->json(["products" => $products]); 
    }

    public function get_by_category($category_id , $request){
      $category = Category::find($category_id);
      if(!$category)
      return response()->json(["message" => "category not found"] , 404);
      ///
      $name = $request->query('name');
      $product_id = $request->query('product_id');
      if($name && !$product_id){  //// get by name
        $products = $category->products;
        foreach($products as $product){
          if($product['name'] == $name)
          return response()->json(["product" => $product]);
        }
        return response()->json(["message" => "this product not found"] , 404);
      }
      else if(!$name && $product_id){  //// get by id
        $products = $category->products;
        foreach($products as $product){
          if($product['id'] == $product_id)
          return response()->json(["product" => $product]);
        }
        return response()->json(["message" => "this product not found"] , 404);
      }
      else if(!$name && !$product_id){
        $products = $category->products;
        return response()->json(["products" => $products]);
      }
      else{
        return response()->json(["message" => "you should serch by name or id not both"] , 422);
      }
    }

    public function create_product($request , $company , $category_id){
      $request->validate([
        "name" => "required|string" ,
        "image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
      ]);
       $category = Category::find($category_id);
       if(!$category)
       return response()->json(["message" => "category not found"] , 404);
       $products = $category->products;
       $image = time().'.'.$request->image->extension();
       $request->image->move(public_path('storage/products_images'), $image);
       $product = [
        "id" => \str_pad(mt_rand(0, 9999999), 7 , '0', STR_PAD_LEFT),
        "name" => $request->name , 
        "image" => "/products_images/".$image ,
        "rate" => 0  ,
        "features" => [] ,
       ];
       array_push($products , $product);
       $category->products = $products;
       $category->save();
       $this->store_in_company($company , $products);
       return response()->json(["products" => $products]);
    }

    public function update_product($request , $company , $category_id , $product_id){
      $request->validate([
         "name" => "required|string"
      ]);
      $category = Category::find($category_id);
      if(!$category)
      return response()->json(["message" => "catagory not found"], 404);
      $products = $category->products;
      foreach($products as $product){
          if($product['id'] == $product_id){
              $index = array_search($product , $products);
              $product['name'] = $request->name;
              $products[$index] = $product;
           }
       } 
       if(!isset($index))
       return response()->json(["message" => "product not found"], 404);
       $category->products = $products;
       $category->save();
       $this->store_in_company($company , $products);
       return response()->json(["product" => $products[$index]] , 200);
    }

  public function delete_product($company , $category_id , $product_id){
    $category = Category::find($category_id);
    if(!$category)
    return response()->json(["message" => "catagory not found"], 404);
    $products = $category->products ;
    foreach($products as $product){
      if($product['id'] == $product_id){
        $index = array_search($product , $products);
        $deleted_product = $products[$index];
        File::delete(public_path('storage'.$product['image']));
        unset($products[$index]);
        $products = array_values($products);
      }
       }
       if(!isset($index))
       return response()->json(["message" => "product not found"], 404);
       $category->products = $products;
       $category->save();
       $this->store_in_company($company , $products);
       return response()->json(["product" => $deleted_product] , 200);
    }

    public function get_products($request){
      $category_id = $request->query('category_id');  
      $company_id = $request->query('company_id');
      if(!$company_id)
      $company_id = auth()->id();
      if($category_id)
      return $this->get_by_category($category_id , $request);
      ///
      else if($company_id)
      return $this->get_by_company($company_id);
      ///
      else 
      return response()->json(["message" => "you should enter company_id or category_id for search"]);
    }

  public function create_features($request , $company , $category_id , $product_id){
    $category = Category::find($category_id);
    if(!$category)
    return response()->json(["message" => "category not found"] , 404);
    $features = $category->features;
    $products = $category->products;
    foreach($products as $product){
      if($product['id'] == $product_id){
        $index = array_search($product , $products);
        $count = 0;
        foreach($features as $feature){
            $count++;
            $request->validate(["id".$count => "required" , "value".$count => "required"]);
            $id = $request->input('id'.$count);
            $features_product = $product['features'];
            if(count($features_product) != 0){
              foreach($features_product as $feature_product){
                if($feature_product['id']==$id){
                 return response()->json(["message" => $feature_product['name']." "."alraedy exists"]);
                }
              } 
            }
            if($feature['id'] == $id){
              $newfeature = [
                "id" => $id,
                "name" => $feature['name'] ,
                "value" => $request->input('value'.$count)
              ];
              array_push($product['features'] , $newfeature);
            }
        }
        $products[$index] = $product; 
      }
    }
    if(!isset($index))
    return response()->json(["message" => "product not found"], 404);
    $category->products = $products;
    $category->save();
    $this->store_in_company($company , $products);
    return response()->json(["product" => $products[$index]] , 200);
  }
  //// product_id & feature_id in url , cat_id , com_id in params
  public function update_features($request , $company , $category_id , $product_id , $feature_id){
    $category = Category::find($category_id);
    if(!$category)
    return response()->json(["message" => "category not found"] , 404);
    $products = $category->products;
    foreach($products as $product){
      if($product['id'] == $product_id){
        $index = array_search($product , $products);
        $features = $product['features'];
        foreach($features as $feature){
         if($feature['id'] == $feature_id){
          $feature_index = array_search($feature , $features);
          $feature['value'] = $request->value;
          $features[$feature_index] = $feature;
          $product['features'] = $features;
         }
        }
        if(!isset($feature_index))
        return response()->json(["message" => "feature not found"], 404);

        $products[$index] = $product; 
      }
    }
    if(!isset($index))
    return response()->json(["message" => "product not found"], 404);
    $category->products = $products;
    $category->save();
    $this->store_in_company($company , $products);
    return response()->json(["product" => $products[$index]] , 200);
  }

  public function delete_feature($company , $category_id , $product_id , $feature_id){
    $category = Category::find($category_id);
    if(!$category)
    return response()->json(["message" => "category not found"] , 404);
    $products = $category->products;
    foreach($products as $product){
      if($product['id'] == $product_id){
        $index = array_search($product , $products);
        $features = $product['features'];
        foreach($features as $feature){
         if($feature['id'] == $feature_id){
          $feature_index = array_search($feature , $features);
          $deleted_feature = $features[$feature_index];
          unset($features[$feature_index]);
          $features = array_values($features);
          $product['features'] = $features;
         }
        }
        if(!isset($feature_index))
        return response()->json(["message" => "feature not found"], 404);

        $products[$index] = $product; 
      }
    }
    if(!isset($index))
    return response()->json(["message" => "product not found"], 404);
    $category->products = $products;
    $category->save();
    $this->store_in_company($company , $products);
    return response()->json(["feature" => $deleted_feature] , 200);
  }
}