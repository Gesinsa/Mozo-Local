<?php

/* Funciones  */

function Ocupar($Mesa, $Ocupado = true)
{
   $DB = new DB();

   if ($Ocupado) {
      $DB->Update("update PVBDMESA set  MA_OCUPA = '*', MA_PAGO = '' where MA_CODIGO = '{$Mesa}'");
      return 0;
   }

   $DB->Update("update PVBDMESA set  MA_OCUPA = '' where MA_CODIGO = '{$Mesa}'");
}

function Config()
{
   require_once("./Class/Session.php");
   $Session = new Session();
   $DB = new DB();
// ISNULL(AREATABLETA,0)

   $Data = $DB->query("select ISNULL(departamentofijo, 0) Departamento,ISNULL(AREATABLETA,0) as Area,
   personaenmesat as Personas 
   from pvbdproc");


   $Data2 = $DB->query("SELECT formato78, ARTIXDIAS,grupoXDIA from fabdproc");


   $Session->Edit("Config", [
      "DepartFijo" => $Data[0]['Departamento'],
      "Area" => $Data[0]['Area'],
      "Personas" => $Data[0]['Personas'],
      "Formato" => $Data2[0]['formato78'],
      "Articuoloxdias" => $Data2[0]['ARTIXDIAS'],
      "grupoxdias" => $Data2[0]['grupoXDIA']
   ]);
}


/* Valid login */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
   $SSID = new SSID();
   $SSID->Validate();
}


/* Method Get */
Route::POST('Login', function () {
   require_once("./Auth/Auth.php");
   require_once("./Class/Session.php");
   $Auth = new Auth();
   $Session = new Session();

   $Auth->login('', $_POST['PIN']);
   $Data = $Auth->Data();

   if (count($Data) != 0) {
      session_regenerate_id();

      $Session->Edit("UserData", [
         "Session_ID" => session_id(),
         "Codigo" => $Data['codigo'],
         "Nombre" => $Data['nombre'],
         "Acceso" => $Data['sc'],
         "Config" => true,
         "Inicio" => $Data['ini'],
         "Fin" => $Data['fin'],
         "Print" => $Data['Prints'],
      ]);


      Config();

      $Session->Edit('Article2', '');

      $Session->Edit('Article', '');
      $Session->Edit('Area', '');
      $Session->Edit('Mesa', '');
      $Session->Edit('Depen', '');
      $Session->Get('Area_Dept', '');

      $Session->Edit("ShoppingCart");
      $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

      echo json_encode(["succes" => true]);
   } else {
      echo json_encode(["succes" => false]);
   }
});


Route::Get('', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $IDSS = $Session->Get("UserData", ['Session_ID' => "...Not Session ..."]);
   $Session->Edit("Totals", ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

   if (session_id() != "" && session_id() == $IDSS['Session_ID']) {
      $Formato = $Session->GetProperty("Config", "Formato", 0);

      if ($Session->Get("ShoppingCart") == []) {
         if (intval($Formato) == 4) {
            header("Location: Mesa");
            return 0;
         }

         header("Location: Areas");
         return 0;
      }

      header("Location:Menu");
      return 0;
   }


   View::make('Login');
});



// Areas
Route::Get('Areas', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Formato = $Session->GetProperty("Config", "Formato", 0);

   if (intval($Formato) == 4) {
      header("Location: Mesa");
      return 0;
   }


   $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   $Session->Edit("ShoppingCart");

   Ocupar($Session->Get('Mesa', ''), false);
   $Session->Edit('Mesa', '');
   $Session->Get('Area_Dept', '');
   $Session->Edit('Depen', '');
   $Session->Edit('Article', '');
   $Session->Edit('Factura', '');
   View::make('Areas');
});

// Mesa
Route::Get('Mesa', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Formato = $Session->GetProperty("Config", "Formato", 0);

   if ($Session->Get("ShoppingCart") != [] &&  $Session->Get('Mesa', '') != '') {
      header("Location: Menu");
      return 0;
   }

   if (intval($Formato) != 4 && $Session->Get('Area', '') == '') {
      header("Location: Areas");
      return 0;
   }

   Ocupar($Session->Get('Mesa', ''), false);

   $Session->Edit('Mesa', '');
   $Session->Edit('Depen', '');
   $Session->Edit('Article', '');
   $Session->Get('Area_Dept', '');
   $Session->Edit('ShoppingCart');
   $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   View::make('Mesas');
});


Route::Get('SubMesa', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa = $Session->Get('Mesa', '');
   if ($Mesa == '') {
      header("Location: Mesa");
      return 0;
   }

   $DB = new DB();
   $Data = $DB->query("SELECT rtrim(MA_CODIGO) as Mesa,rtrim(MA_DEPEN) as Depen from IVBDHETE 
      Where (MA_CODIGO = '{$Mesa}' OR MA_DEPEN = '{$Mesa}') and he_tipfac=''");

   $Count = count($Data);


   if ($Count == 0 || ($Session->Get("ShoppingCart") != [] &&  $Mesa != '')) {
      $Session->Edit('Area_Dept', '');
      header("Location: Menu");
      return 0;
   }

   if ($Count == 1) {
      $Session->Edit('Mesa', $Data[0]["Mesa"]);
      $Session->Edit('Depen', $Data[0]["Depen"]);

      header("Location: Pedido");
      return 0;
   }

   $Session->Edit('Depen', '');
   $Session->Edit('Article', '');
   $Session->Edit('Area_Dept', '');
   $Session->Edit('ShoppingCart');
   $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

   View::make('SubMesa');
});


Route::Get('Pedido', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Mesa = $Session->Get('Mesa', '');
   if ($Mesa == '') {
      header("Location: Mesa");
      return 0;
   }

   $DB = new DB();
   $Data = $DB->query("SELECT MA_CODIGO from IVBDHETE 
      Where MA_CODIGO = '{$Mesa}'  and he_tipfac=''");
   $Count = count($Data);


   if ($Count == 0 || ($Session->Get("ShoppingCart") != [] &&  $Mesa != '')) {
      $Session->Edit('Area_Dept', '');
      header("Location: Menu");
      return 0;
   }



   $Session->Edit('Article', '');
   $Session->Edit('ShoppingCart');
   $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

   View::make('Pedido');
});


Route::Get('MenuAreas', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Mesa = $Session->Get('Mesa', '');
   $Article = $Session->Get('Article', '');
   $FormatoArea = $Session->GetProperty("Config", "Area", 0);

   if ($Mesa == '') {
      $Session->Edit("ShoppingCart");
      header("Location: Mesa");
      return 0;
   }

   if ($Article != '') {
      header("Location: Article");
      return 0;
   }

   if (intval($FormatoArea) == 0) {
      header("Location: Menu");
      return 0;
   }

   if ($Session->Get("ShoppingCart") == []) {
      $Session->Edit('ShoppingCart');
      $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   }


   Ocupar($Session->Get('Mesa', ''));

   $Session->Edit('Article', '');
   $Session->Edit('Area_Dept', '');
   View::make('Menu2');
});

Route::Get('Menu', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Mesa = $Session->Get('Mesa', '');
   $Article = $Session->Get('Article', '');
   $FormatoArea = $Session->GetProperty("Config", "Area", 0);
   $Area = $Session->Get('Area_Dept', '');


   if ($Mesa == '') {
      $Session->Edit("ShoppingCart");
      header("Location: Mesa");
      return 0;
   }

   if ($Article != '') {
      header("Location: Article");
      return 0;
   }

  

   if (intval($FormatoArea) != 0 && $Area == "") {
      header("Location: MenuAreas");
      return 0;
   }

   if ($Session->Get("ShoppingCart") == []) {
      $Session->Edit('ShoppingCart');
      $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   }


   Ocupar($Session->Get('Mesa', ''));

   $Session->Edit('Article', '');
   View::make('Menu');
});

Route::Get('Article', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Mesa = $Session->Get('Mesa', '');
   $Article = $Session->Get('Article', '');

   if ($Mesa == '') {
      $Session->Edit("ShoppingCart");
      header("Location: Mesa");
      return 0;
   }

   if ($Article == '') {
      header("Location: Menu");
      return 0;
   }


   if ($Session->Get('ShoppingCart') == []) {
      $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   }

   View::make('Article');
});


Route::Get('Cart', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa = $Session->Get('Mesa', '');

   if ($Mesa == '') {
      $Session->Edit("ShoppingCart");
      header("Location: Mesa");
      return 0;
   }

   if ($Session->Get('ShoppingCart') == []) {
      $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
   }

   Ocupar($Session->Get('Mesa', ''));
   View::make('Cart');
});

Route::Get('Print', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa = $Session->Get('Mesa', '');

   if ($Mesa == '') {
      $Session->Edit("ShoppingCart");
      header("Location: Mesa");
      return 0;
   }

   View::make('Print');
});



Route::POST('LogOut', function () {
   require_once("./Auth/Auth.php");
   Auth::logOut();
});

Route::POST('Config', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $FormatoArea = $Session->Get("Config", []);
   echo json_encode($FormatoArea);
});

Route::POST('Area', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Session->Edit('Area', $_POST["Area"], '');
   echo json_encode(
      ["Area" => $Session->Get('Area', '')]
   );
});

Route::POST('Area_Depart', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   $Session->Edit('Area_Dept', $_POST["Code"], '');
   $Area = $Session->Get('Area_Dept', '');
   echo json_encode(["Area_Depart" => $Area]);
});

Route::POST('HeaderPrint', function () {
   require_once("./models/HeaderPrint.php");
});

Route::POST('Note', function () {
   $Json = require_once("./models/Note.php");
   echo json_encode($Json);
});

Route::POST('Liberar', function () {
   require_once("./Class/Session.php");
   $Session = new Session();

   Ocupar($Session->Get('Mesa', ''), false);
   echo json_encode([]);
});

Route::POST('Mozo', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Data = [
      "Codigo" => $Session->GetProperty("UserData", 'Codigo', ''),
      "Nombre" => $Session->GetProperty("UserData", 'Nombre', ''),
      "Print" =>  $Session->GetProperty("UserData", 'Print', 0),
      "Acceso" =>  $Session->GetProperty("UserData", 'Acceso', 0),
   ];

   echo json_encode($Data);
});

Route::POST('Mesa', function () {
   require_once("./Class/Session.php");

   $Session = new Session();

   $Mesa = isset($_POST["Mesa"]) ?  trim($_POST["Mesa"]) : "";

   if ($Mesa == "") {
      Ocupar($Session->Get('Mesa', ''), false);
   }

   $Session->Edit('Mesa', $Mesa, '');
   $Session->Edit('Depen', $_POST["Depen"], '');

   echo json_encode([]);
});


Route::POST('Depen', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa =  $Session->Get('Mesa', '');
   $Depen = $Session->Get('Depen', '');

   echo json_encode(["Mesa" => $Mesa, "Depen" => $Depen]);
});

Route::POST('Article', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Session->Edit('Article', $_POST["Article"], '');
   echo json_encode(array('code' => $Session->Get('Article', '')));
});


Route::POST('ADDShopping', function () {
   require_once("./models/Add.php");
});


Route::POST('Areas', function () {
   require_once("./models/Areas.php");
});

Route::POST('Mesas', function () {
   require_once("./models/Mesas.php");
});


Route::POST('MesaStatus', function () {
   require_once("./models/MesaStatus.php");
});

Route::POST('SubMesas', function () {
   require_once("./models/SubMesas.php");
});

Route::POST('Pedido', function () {
   require_once("./models/Pedido.php");
});

Route::POST('Resumir', function () {
   require_once("./models/resumen.php");
});

Route::POST('Dividir', function () {
   require_once("./models/Dividir.php");
});

Route::POST('Areas_Departs', function () {
   require_once("./models/Areas_Dept.php");
});

Route::POST('Departa', function () {
   require_once("./models/Depart.php");
});

Route::POST('Articulos', function () {
   require_once("./models/Articulos.php");
});

Route::POST('Articulo', function () {
   require_once("./models/Articulo.php");
});

Route::POST('Existence', function () {
   require_once("./models/Existencia.php");
});


Route::POST('Cart', function () {
   require_once("./models/Shopping.php");
});

Route::POST('Shopping', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   echo json_encode($Session->Get("ShoppingCart"));
});

Route::POST('Totals', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   echo json_encode($Session->Get('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => -1, 'Discount' => 0]));
});

Route::POST('ClearShopping', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Session->Edit("ShoppingCart");
   $Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

   echo json_encode([]);
});



Route::POST('ClearMesa', function () {
   $Mesa = isset($_POST['Mesa']) ? trim($_POST['Mesa']) : '';

   if (!empty($Mesa)) {
      Ocupar($Mesa, false);
   }

   echo json_encode([]);
});




Route::POST('Pagar', function () {
   $Json = require_once("./models/Pago.php");
   echo json_encode($Json);
});


Route::POST('Imprimir', function () {
   require_once("./models/Print.php");
   echo json_encode([]);
});



Route::POST('Pagar_Imprimir', function () {
   $Json = require_once("./models/Pago.php");

   if ($Json['status']) {
      require_once("./models/Print.php");
   } else {
      echo json_encode($Json);
   }
});


Route::POST('PrintWaiting', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa = $Session->Get('Mesa', '');
   $Depen = $Session->Get('Depen', '');
   require_once("./models/PrintWaiting.php");
});

Route::POST('AllPrintWaiting', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $Mesa = $Session->Get('Mesa', '');
   $Depen = null;
   require_once("./models/PrintWaiting.php");
});


Route::POST('Name', function () {
   require_once("./models/Name.php");
});

Route::POST('Search', function () {
   require_once("./models/Search.php");
});


Route::POST('DepartFijo', function () {
   require_once("./Class/Session.php");
   $Session = new Session();
   $DepartFijo = $Session->GetProperty("Config", "DepartFijo", 0);
   echo json_encode(["Departamento" => intval($DepartFijo)]);
});
