<?php







use Illuminate\Database\Capsule\Manager as Capsule;







use Gregwar\Image\Image;







use Carbon\Carbon;







use \Curl\Curl;







use Dompdf\Dompdf;















//google analytics







define('VIEW', '182258569');







define('SERVICE_ACCOUNT', __DIR__ . '/../../public_html/json/expanded-talon-342207-56754ab3791b.json');







define('DOMAIN', 'https://www.maxipro.co.id/');







defined('BASEPATH') or exit('No direct script access allowed');







require APPPATH . '/libraries/REST_Controller.php';











class TeknisiAPI extends REST_Controller



{















	//example cron jobs -> wget -q https://maxipro.id/teknisi/statusPenjualanRealtime>/dev/null 2>&1















	//Mail Authentication







	private $Email_Host       	= 'maxipro.id';







	private $Email_Username   	= 'sales@maxipro.id';







	private $Email_Password   	= 'Maxipro123';







	private $Email_Port       	= 465;







	private $Email_SMTPSecure 	= 'ssl';











	//BeeCloud Authentication







	private $ContentType 		= "Content-Type: application/json";







	private $AuthorizationPT 	= "Authorization: Bearer eyJ0eXAiOiJKV1MiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcHAuYmVlY2xvdWQuaWQiLCJqdGkiOiI4MjcwZWM0MGRlN2NmMmMwNjRkODgzOGEyZWIwMDk2NyIsImRibmFtZSI6IjE0MzM5bWF4aXByb2dyb3VwaW5kIiwiZGJob3N0IjoiMTAuMTMwLjIyLjExMiIsInVzZXJfaWQiOiIzIn0.qzS9xac0oj-8e8nb7-z3oDSI-63_12Bo3C71HjiGZ4Y";







	private $AuthorizationUD 	= "Authorization: Bearer eyJ0eXAiOiJKV1MiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcHAuYmVlY2xvdWQuaWQiLCJqdGkiOiI4MjcwZWM0MGRlN2NmMmMwNjRkODgzOGEyZWIwMDk2NyIsImRibmFtZSI6IjE1NjI2c3RlcGhlbnNhbjg2IiwiZGJob3N0IjoiMTAuMTMwLjM2LjEzOSIsInVzZXJfaWQiOiIzIn0.J2_s7tFOKpepP-kyEyfVrO16HpyWV61L-vV9_llSvzs";















	//Google Sheet Api







	private $spreadsheetId 			= "1Zc0Qxcb38HPdKgKxauQcyhH4mm_QbhVBofQORUDwuSQ"; //CHECKLIST PRODUCT --> Share All







	private $spreadsheetIdLoker 	= "1aWl-qQBfLCVRyUZHzeDK9h9MY03QkGY3NBf8Xi33oLQ"; //LOKER --> Busdev - Bayu











	//Qontak Integration Maxipro







	private $mxp_channel_id 	= '2f0d4646-3d8b-4c5e-ab78-4d3249d27daf';











	//Pancake Maxipro







	private $pancake_numberForm 	= "6281133330380";







	private $pancake_accessToken 	= "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI0MDdhMjlhYi1jNmI5LTRhMDktOWFlMS04Y2VjNTM5MmM0OTAiLCJpYXQiOjE2NDY2NDY1MzksImZiX25hbWUiOiJDaHJpc3QgTWF4aXBybyIsImZiX2lkIjoiMzU3OTg5NTAyNDY5NzYyIiwiZXhwIjoxNjU0NDIyNTM5fQ.XsdKxfBTrWc40Wrz9Q_YKnjGqJ9oaN18G-1nAVVXVWA";











	//Qontak Authentication







	private $postQontak 			= array(







		'username' 					=> 'stephen@maxipro.co.id',







		'password' 					=> 'password',







		'grant_type' 				=> 'password',







		'Content-Type' 				=> 'application/x-www-form-urlencoded'







	);















	//Qiscus Authentication







	private $ChannelId 				= 648;







	private $NameSpace 				= "322de83b_1433_4fd5_a608_4890cd809744";







	private $QiscusAppId 	 		= "ivabu-pwzttzi0nzrcaz4";







	private $AppId 	 				= "3785";







	private $AuthorizationQs 		= "4EEmaMeDajDAqnzm6LsY";















	// private $mxp_access_token 		= "Wq_IgF58_cEDB9pQVk2BjcDbN7ogPWpXtXhfrfUSWiQ";







	// private $mxp_channel_id 		= '2f0d4646-3d8b-4c5e-ab78-4d3249d27daf';















	//Trello Authentication







	private $trelloKey 				= "9c1e8940f25f5c19b872c54d796f8715";







	private $trelloToken 			= "e73b16d314356798286cba0f9c115ebf5fa557bc60ea181a6d7b6b17f9655ab0";







	private $trelloIDListNewSales 	= "603718f355c0152d858228ed";







	private $trelloIDChrist 		= "5ae15a1095902dd33f2cfdd9";







	private $trelloIDIki 			= "5e79beeeb2048887d8b05793";







	private $trelloIDWilly 			= "5f729808584b7f49fd6c9180";







	private $trelloIDPajak 			= "5f9fc5f5fb68214125a2435e";















	//ClickUp Authentication







	private $clickUpToken 			= "pk_5894107_VNXLG0XJVISZUEI30LZ5BF4NZUL6ABHZ";







	private $clickUpIDChrist 		= 5901969;







	private $clickUpIDWilly 		= 5903937;







	private $clickUpIDSales 		= 5901970;



	public $orderpembelian_comercial_invoice =0;











	//Pancake Maxipro







	// private $pancake_numberForm     = "6281386868672";







	// private $pancake_accessToken    = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIyNTA5MDFmNy1hNDE1LTRkMmQtYTllYy0xMjkwZDY0YjAzNDgiLCJpYXQiOjE2NTUyNzYxOTAsImZiX25hbWUiOiJNYXhpcHJvIiwiZmJfaWQiOiIxMDM5NjcwNzEyMDg2MDQiLCJleHAiOjE2NjMwNTIxOTB9.6eIDeUQd9ln5QfbNoTomLaKZp9hcMJIAwYj3J1PPm_E";















	public function __construct()







	{







		parent::__construct();















		require_once(__DIR__ . '/../libraries/WooCommerce/lib/woocommerce-api.php');















		$this->load->helper('download');







		$this->middleware->website('maintenance');







		$this->load->library('ciqrcode');



		$this->load->model('AddqcModel');



		$this->load->model('BarangModel');



		$this->load->helper('youtube_helper');







		$this->load->model('GudangModel');



		$this->load->model('RakModel');



		$this->load->model('BarangcategoryModel');



		$this->load->model('BarangsubcategoryModel');



		$this->load->model('PenawaranModel');


		   $this->load->library('session');
        $this->load->helper('url');
		$options = array(







			'ssl_verify'      => false,







		);















		$this->client = new WC_API_Client('https://maxipro.co.id', 'ck_962fd1160a0291a04914d13415ad51c17f46abc8', 'cs_0e131189ce725d5fe65bb19ae015281f470fc8df', $options);















		$this->data['config'] 				= ConfigModel::find(1);







		$this->data['seo'] 					= SeoModel::find(1);







		$this->data['sosmed'] 				= SosmedModel::asc()->get();







		$this->data['cta']					= CtaModel::find(1)->first();







		$this->data['config_store']			= ConfigStoreModel::find(1);







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (!empty($this->session->userdata('teknisi'))) {







			if ($this->session->has_userdata('teknisi_id')) {



				$teknisi_status = $this->session->userdata('teknisi_status');



				$teknisi_id = $this->session->userdata('teknisi_id');



				$teknisi = TeknisiModel::find($teknisi_id);







				$this->data['teknisi'] = $teknisi;

			}











			$this->data['masterppn'] 		= PpnModel::find(1);







			$this->data['masterrmbtousd'] 	= RmbtousdModel::find(1);







			$this->data['convertrmbtoidr'] 	= RmbtousdModel::find(2);







			$this->data['convertusdtoidr'] 	= RmbtousdModel::find(3);















			//rmb & usd to idr







			$this->data['rmbtoidr'] 		= ConvertCurrency::RMBtoIDR();







			$this->data['usdtoidr'] 		= ConvertCurrency::USDtoIDR();











			$this->data['teknisi'] 			= TeknisiModel::find($this->session->userdata('teknisi'));



			//    var_dump('teknisi'.	$this->session->userdata('teknisi')	);







			$this->data['count_keluhan'] 	= KeluhanModel::where('status', 0)->desc()->get();







			$teknisi 						= TeknisiModel::find($this->session->userdata('teknisi_id'));







			if ($teknisi->status == 'teknisi') {







				$this->data['status_name'] 	= 'Teknisi';







				$this->data['countpiutang'] = ServiceModel::where('id_teknisi', $teknisi->id)->where('status', 3)->where('status_bayar', 0)->get();

			} elseif ($teknisi->status == 'kepala_teknisi') {







				$this->data['status_name'] 	= 'Kepala Teknisi';







				$this->data['countpiutang'] = ServiceModel::where('status', 3)->where('status_bayar', 0)->get();

			} elseif ($teknisi->status == 'manager' || $teknisi->status == 'super_admin') {







				$this->data['status_name'] 	= 'Super Admin';







				$this->data['countpiutang'] = ServiceModel::where('status', 3)->where('status_bayar', 0)->get();

			} elseif ($teknisi->status == 'kepala_toko') {







				$this->data['status_name'] 	= 'Kepala Toko';







				$this->data['countpiutang'] = ServiceModel::where('status', 3)->where('status_bayar', 0)->get();

			} elseif ($teknisi->status == 'admin') {







				$this->data['status_name'] 	= 'Admin';







				$this->data['countpiutang'] = ServiceModel::where('status', 3)->where('status_bayar', 0)->get();

			} else {







				$this->data['status_name'] 	= 'Customer Service';







				$this->data['countpiutang'] = ServiceModel::where('status', 3)->where('status_bayar', 0)->get();

			}







			//  		$this->data['dokumen'] 			= DokumenNotificationModel::where('id_teknisi', $teknisi->id)->where('status', 0)->get();







			//  		$this->data['countdokumen'] 	= count($this->data['dokumen']);







		}















		if (isset($_SERVER["HTTP_REFERER"])) {







			$this->data['lastlinkpage'] 	= $_SERVER["HTTP_REFERER"];

		}















		$startSelectDate 					= 2019;







		$endSelectDate 						= date('Y');







		$differenceDate 					= (int) $endSelectDate - (int) $startSelectDate;















		$dateData 							= array();







		for ($i = 0; $i <= $differenceDate; $i++) {







			$dateData[] 					= $startSelectDate + $i;

		}















		$this->data['dateData'] 			= $dateData;















		$this->data['linkpage'] 			= 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];







		$this->blade->sebarno('ctrl', $this);



	}



	public function addqc()



	{







		if ($this->session->userdata('teknisi')) {



			$aa = $this->session->userdata('teknisi');



			if ($aa == true) {







				$this->db->select('teknisi.*');



				$this->db->from('position_karyawan');



				$this->db->join('teknisi', 'position_karyawan.id = teknisi.id_position');



				$this->db->where_in('position_karyawan.name', array('Teknisi', 'Gudang'));















				$data['teknisi'] = $this->db->get()->result_array();







				$data['barang'] = $this->db->order_by('name', 'asc')



					->like('new_kode', 'M') // Add this line for the like condition



					->get('barang')



					->result_array();







				echo $this->blade->viewes('website.teknisi.qc.create', $data);

			}

		} else {



			redirect(base_url("login"));

		}

	}
 private function validate_teknisi_id() {
        $teknisi_id = $this->session->userdata('teknisi_id');

        if (!$teknisi_id) {
            $teknisi_id = $this->input->post('teknisi_id');
        }

        if (!$teknisi_id) {
            $this->response([
                'success' => false,
                'message' => 'Teknisi tidak ada'
            ], 401);
            return false;
        }

        // Assume TeknisiModel is autoloaded or included
        $teknisi = TeknisiModel::find($teknisi_id);

        if (!$teknisi) {
            $this->response([
                'success' => false,
                'message' => 'Anda Belum Login'
            ], 401);
            return false;
        }

        return $teknisi;
    }






	public function prosesadd()



	{







		if (!empty($this->session->userdata('teknisi'))) {



			$code_mesin = $this->input->post('code_mesin');



			$type_mesin = $this->input->post('type_mesin');



			$name_operator = $this->input->post('name_operator');



			$url_video = $this->input->post('url_video');







			$url = $url_video;



			$cari = "embed";



			$posisi = strpos($url, $cari);



			if ($posisi != null) {



				$embed_code =  __getYouTubeEmbeddedURL1($url);

			} else {



				$embed_code =  __getYouTubeEmbeddedURL($url);

			}



			if (isset($_FILES['url_form']['name'])) {







				$this->load->library('upload');



				$config['upload_path'] = APPPATH . '../public_html/images/qc/';



				$config['allowed_types'] = 'gif|jpg|png';



				$this->upload->initialize($config);



				if ($this->upload->do_upload('url_form')) {



					$uploaded = $this->upload->data();







					$arr['url_form'] = $uploaded['file_name'];

				}

			}







			$dataaddqc = [







				"code_mesin" => $code_mesin,



				"url_form" => $arr['url_form'],



				"url_video" => $embed_code,



				"type_mesin" => $type_mesin,



				"name_operator" => $name_operator,



			];







			$this->db->insert('addqc', $dataaddqc);



			redirect(base_url('qc'));

		} else {



			redirect(base_url('qc'));

		}

	}











	public function index()



	{







		echo 'index';







		return;

	}











	public function checked()



	{



		$result = $this->db->get('addqc')->result();











		echo $this->blade->viewes('website.teknisi.qc.check');

	}







	//Start API











	//API Get Dashboard



	public function dashboard_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {







			$teknisi_id = $this->input->get('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}















		$alltagsale         = PenjualanoptionModel::where('status', '1')->asc()->get();







		$grouptagsale       = PenjualanoptionModel::where('status', '1')->groupBy('id_category')->asc()->get();







		$adminsale          = TeknisiModel::where('status_sales', '1')->asc()->get();







		$companysale        = array('UD', 'PT');



		if (!$this->input->get('pertanggal') && !$this->input->get('perbulan') && !$this->input->get('all')) {







			$all            = 0;







			$pertanggal     = 0;







			$perbulan       = 1;

		} else {







			$all            = $this->input->get('all');







			$pertanggal     = $this->input->get('pertanggal');







			$perbulan       = $this->input->get('perbulan');

		}



		if ($pertanggal == 1 && $perbulan == 0 && $all == 0) {







			$tgl_awal       = $this->input->get('tgl_awal');







			$tgl_akhir      = $this->input->get('tgl_akhir');















			$penjualan      = PenjualanModel::where('status_deleted', '0')->whereDate('tgl_transaksi', '>=', $tgl_awal)->whereDate('tgl_transaksi', '<=', $tgl_akhir)->desc()->get();







			$newcustomer    = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->whereDate('tgl_transaksi', '>=', $tgl_awal)->whereDate('tgl_transaksi', '<=', $tgl_akhir)->desc()->get();

		} elseif ($pertanggal == 0 && $perbulan == 1 && $all == 0) {



			// dd('a');



			if (!$this->input->get('bulan') && !$this->input->get('tahun')) {







				$tgl_awal   = date('m');







				$tgl_akhir  = date('Y');

			} else {







				$tgl_awal   = $this->input->get('bulan');







				$tgl_akhir  = $this->input->get('tahun');

			}















			if (date('d') < 28 && $tgl_awal == date('m')) {















				//now







				$nowmonth       = $tgl_awal;







				$nowyear        = $tgl_akhir;







				$nowstart       = $nowyear . '-' . $nowmonth . '-01';







				$nowend         = $nowyear . '-' . $nowmonth . '-' . date('d');















				//old







				$newdate        = strtotime($nowyear . '-' . $nowmonth);







				$olddate        = date('Y-m', strtotime("-1 months", $newdate));















				$explodedate    = explode('-', $olddate);







				$oldmonth       = $explodedate[1];







				$oldyear        = $explodedate[0];







				$oldstart       = $oldyear . '-' . $oldmonth . '-01';







				$oldend         = $oldyear . '-' . $oldmonth . '-' . date('d');















				//now







				$penjualan      = PenjualanModel::where('status_deleted', '0')->whereDate('tgl_transaksi', '>=', $nowstart)->whereDate('tgl_transaksi', '<=', $nowend)->desc()->get();







				$newcustomer    = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->whereDate('tgl_transaksi', '>=', $nowstart)->whereDate('tgl_transaksi', '<=', $nowend)->desc()->get();











				// $penjualan = PenjualanModel::where('status_deleted', '0')



				// ->whereBetween('tgl_transaksi', [$nowstart, $nowend])



				// ->orderByDesc('tgl_transaksi')



				// ->get();







				// $newcustomer = PenjualanModel::where('status_deleted', '0')



				// ->where('id_opsi', 6)



				// ->whereBetween('tgl_transaksi', [$nowstart, $nowend])



				// ->orderByDesc('tgl_transaksi')



				// ->get();







				// old







				$penjualanold   = PenjualanModel::where('status_deleted', '0')->whereDate('tgl_transaksi', '>=', $oldstart)->whereDate('tgl_transaksi', '<=', $oldend)->desc()->get();







				$newcustomerold = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->whereDate('tgl_transaksi', '>=', $oldstart)->whereDate('tgl_transaksi', '<=', $oldend)->desc()->get();







				// $penjualanold = PenjualanModel::where('status_deleted', '0')



				// ->whereBetween('tgl_transaksi', [$oldstart, $oldend])



				// ->orderBy('tgl_transaksi', 'desc')



				// ->get();







				// $newcustomerold = PenjualanModel::where('status_deleted', '0')



				// ->where('id_opsi', 6)



				// ->whereBetween('tgl_transaksi', [$oldstart, $oldend])



				// ->orderBy('tgl_transaksi', 'desc')



				// ->get();







			} else {















				//now







				$nowmonth       = $tgl_awal;







				$nowyear        = $tgl_akhir;















				//old







				$newdate        = strtotime($nowyear . '-' . $nowmonth);







				$olddate        = date('Y-m', strtotime("-1 months", $newdate));















				$explodedate    = explode('-', $olddate);







				$oldmonth       = $explodedate[1];







				$oldyear        = $explodedate[0];















				//now







				$penjualan      = PenjualanModel::where('status_deleted', '0')->whereMonth('tgl_transaksi', '=', $nowmonth)->whereYear('tgl_transaksi', '=', $nowyear)->desc()->get();







				$newcustomer    = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->whereMonth('tgl_transaksi', '=', $nowmonth)->whereYear('tgl_transaksi', '=', $nowyear)->desc()->get();















				// old







				$penjualanold   = PenjualanModel::where('status_deleted', '0')->whereMonth('tgl_transaksi', '=', $oldmonth)->whereYear('tgl_transaksi', '=', $oldyear)->desc()->get();







				$newcustomerold = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->whereMonth('tgl_transaksi', '=', $oldmonth)->whereYear('tgl_transaksi', '=', $oldyear)->desc()->get();











				//now



				// $penjualan = PenjualanModel::where('status_deleted', '0')



				//     ->whereBetween('tgl_transaksi', [$nowmonth, $nowyear])



				//     ->desc()



				//     ->get();







				// $newcustomer = PenjualanModel::where('status_deleted', '0')



				//     ->where('id_opsi', 6)



				//     ->whereBetween('tgl_transaksi', [$nowmonth, $nowyear])



				//     ->desc()



				//     ->get();







				// // old



				// $penjualanold = PenjualanModel::where('status_deleted', '0')



				//     ->whereBetween('tgl_transaksi', [$nowmonth, $nowyear])



				//     ->desc()



				//     ->get();







				// $newcustomerold = PenjualanModel::where('status_deleted', '0')



				//     ->where('id_opsi', 6)



				//     ->whereBetween('tgl_transaksi', [$nowmonth, $nowyear])



				//     ->desc()



				//     ->get();















			}















			//perbandingan 2 tahun ke belakang







			$newData        = array();







			$dateName       = array();







			for ($i = 0; $i <= 2; $i++) {















				$thisMonth  = $tgl_akhir . '-' . $tgl_awal;







				if ($thisMonth == date('Y-m')) {















					$explodeStartYear   = explode('-', $thisMonth . '-01');







					$explodeEndYear     = explode('-', date('Y-m-d'));







					$startYear          = (int) $explodeStartYear[0] - $i;







					$endYear            = (int) $explodeEndYear[0] - $i;







					$startDate          = $startYear . '-' . $explodeStartYear[1] . '-' . $explodeStartYear[2];







					$endDate            = $endYear . '-' . $explodeEndYear[1] . '-' . $explodeEndYear[2];















					$penjualanoldyear   = PenjualanModel::where('status_deleted', '0')->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $endDate)->desc()->get();

				} else {















					$arrayYear          = (int) $tgl_akhir - $i;















					$penjualanoldyear   = PenjualanModel::where('status_deleted', '0')->whereMonth('tgl_transaksi', '=', $tgl_awal)->whereYear('tgl_transaksi', '=', $arrayYear)->desc()->get();

				}















				$newData[]          = $penjualanoldyear;







				$dateName[]         = (int) $tgl_akhir - $i;







				$dateBg[]           = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

			}

		} else {







			$tgl_awal       = 'semua';







			$tgl_akhir      = 'semua';















			$penjualan      = PenjualanModel::where('status_deleted', '0')->desc()->get();







			$newcustomer    = PenjualanModel::where('status_deleted', '0')->where('id_opsi', 6)->desc()->get();

		}



		$idpenjualan        = array();







		foreach ($penjualan as $key => $value) {







			$idpenjualan[]  = $value->id;

		}











		$penjualandetail    = PenjualandetailModel::whereIn('id_penjualan', $idpenjualan)->orderBy('name', 'asc')->get();







		$detailgroup        = PenjualandetailModel::whereIn('id_penjualan', $idpenjualan)->groupBy('item_code')->orderBy('name', 'asc')->get();















		$salebyproduct      = SalesAnalysis::saleByProductWithoutCategory($detailgroup, $penjualandetail);







		$salebytag          = SalesAnalysis::saleByGroupTag($grouptagsale, $penjualan);







		$salebysales        = SalesAnalysis::saleBySales($adminsale, $penjualan);







		$salebycompany      = SalesAnalysis::saleByCompany($companysale, $penjualan);















		$ratiosalebytaggraph        = SalesAnalysis::saleByGroupTagRatioGraph(2, $newData, $grouptagsale);







		$ratiosalebysalesgraph      = SalesAnalysis::saleBySalesRatioGraph(2, $newData, $salebysales['adminsale']);







		$ratiosalebycompanygraph    = SalesAnalysis::saleByCompanyRatioGraph(2, $newData, $companysale);























		//old







		$oldidpenjualan         = array();







		foreach ($penjualanold as $key => $value) {







			$oldidpenjualan[]   = $value->id;

		}















		$oldpenjualandetail     = PenjualandetailModel::whereIn('id_penjualan', $oldidpenjualan)->orderBy('name', 'asc')->get();







		$olddetailgroup         = PenjualandetailModel::whereIn('id_penjualan', $oldidpenjualan)->groupBy('item_code')->orderBy('name', 'asc')->get();























		$oldsalebyproduct       = SalesAnalysis::saleByProductWithoutCategory($olddetailgroup, $oldpenjualandetail);







		$oldsalebytag           = SalesAnalysis::saleByGroupTag($grouptagsale, $penjualanold);











		// $data['auth']           = true;







		// $data['msg']            = 'success';







		// $data['pertanggal']         = $pertanggal;







		// $data['perbulan']           = $perbulan;







		// $data['all']                = $all;







		// $data['tgl_awal']           = $tgl_awal;







		// $data['tgl_akhir']          = $tgl_akhir;















		// $data['salebygrouptag']     = $salebytag;







		// $data['salebysales']        = $salebysales;







		// $data['salebycompany']      = $salebycompany;







		// $data['salebyproduct']      = $salebyproduct;







		// $data['newcustomer']        = count($newcustomer);















		// //old







		// $data['oldsalebygrouptag']  = $oldsalebytag;







		// $data['oldsalebyproduct']   = $oldsalebyproduct;







		// $data['oldnewcustomer']     = count($newcustomerold);















		// //old year







		// $data['ratiosalebytaggraph']        = $ratiosalebytaggraph;







		// $data['ratiosalebysalesgraph']      = $ratiosalebysalesgraph;







		// $data['ratiosalebycompanygraph']    = $ratiosalebycompanygraph;







		// $filter_spv = $tgl_akhir . '-' . $tgl_awal;



		// $data['dateName']                   = $dateName;







		// $data['dateBg']                     = $dateBg;



		// $data['allteknisi'] 		= TeknisiModel::where('status', 'teknisi')->where('status_regis', 1)->get();



		// $service 		= ServicespvModel::orderBy('tgl_pelaporan', 'desc')->where('tgl_pelaporan', 'like', '%' . $filter_spv . '%')->where('tgl_pengerjaan', 'like', '%' . $filter_spv . '%')->get();



		// $data['servicespv'] = $service;







		$data['auth'] = true;



		$data['msg'] = 'success';



		$data['pertanggal'] = $pertanggal;



		$data['perbulan'] = $perbulan;



		$data['all'] = $all;



		$data['tgl_awal'] = $tgl_awal;



		$data['tgl_akhir'] = $tgl_akhir;



		$data['salebygrouptag'] = $salebytag;



		$data['salebysales'] = $salebysales;



		$data['salebycompany'] = $salebycompany;



		$data['salebyproduct'] = $salebyproduct;



		$data['newcustomer'] = count($newcustomer);







		// old



		$data['oldsalebygrouptag'] = $oldsalebytag;



		$data['oldsalebyproduct'] = $oldsalebyproduct;



		$data['oldnewcustomer'] = count($newcustomerold);







		// old year



		$data['ratiosalebytaggraph'] = $ratiosalebytaggraph;



		$data['ratiosalebysalesgraph'] = $ratiosalebysalesgraph;



		$data['ratiosalebycompanygraph'] = $ratiosalebycompanygraph;



		$filter_spv = $tgl_akhir . '-' . $tgl_awal;



		$data['dateName'] = $dateName;



		$data['dateBg'] = $dateBg;



		$data['allteknisi'] = TeknisiModel::where('status', 'teknisi')->where('status_regis', 1)->get();



		$service = ServicespvModel::orderByRaw('tgl_pelaporan DESC')->where('tgl_pelaporan', 'like', '%' . $filter_spv . '%')->where('tgl_pengerjaan', 'like', '%' . $filter_spv . '%')->get();







		$data['servicespv'] = $service;







		$id_services = [];



		$id_servicespv = [];



		foreach ($service as $service) {



			$id_services[] = $service->id_service;



			$id_servicespv[] = $service->id;

		}



		// dd($id_servicespv);







		$data['teknisispv'] 			= ServicespvModel::whereIn('id_service', $id_services)->orderBy('tgl_pengerjaan', 'desc')->get();



		$data['spkservice'] 			= SpkModel::whereIn('id_service', $id_services)->get();







		echo json_encode($data);

	}



	//API GET Data User



	public function data_user_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$this->data['teknisi'] = $teknisi;







		$data['position']           = PositionKaryawanModel::orderBy('name', 'asc')->get();







		$data['jabatan']            = JabatanModel::orderBy('name', 'asc')->get();







		$nameFilter                 = $this->input->get('name');







		$positionFilter             = $this->input->get('position');



		if (!$nameFilter) {







			$valuename              = '';

		} else {







			$valuename              = $nameFilter;

		}







		if (!$positionFilter) {







			$valueposition          = 'all';

		} else {







			$valueposition          = $positionFilter;

		}







		$page                       = $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page                   = 0;

		}







		if ($valueposition != 'all') {















			if ($teknisi['status'] != 'manager' && $teknisi['status'] != 'super_admin') {







				$datateknisi        = TeknisiModel::where('status', 'teknisi')->where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->where('id_position', $valueposition)->orderBy('name', 'asc')->get();

			} else {







				$datateknisi        = TeknisiModel::where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->where('id_position', $valueposition)->orderBy('name', 'asc')->get();

			}

		} else {







			if ($teknisi['status'] != 'manager' && $teknisi['status'] != 'super_admin') {







				$datateknisi        = TeknisiModel::where('status', 'teknisi')->where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->orderBy('name', 'asc')->get();

			} else {







				$datateknisi        = TeknisiModel::where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->orderBy('name', 'asc')->get();

			}

		}



		$idTeknisi                  = array();







		foreach ($datateknisi as $key => $value) {







			$idTeknisi[]            = $value->id;

		}



		$total                      = count($idTeknisi);







		$data['datateknisi']        = TeknisiModel::whereIn('id', $idTeknisi)->get();







		$data['nameFilter']         = $valuename;







		$data['positionFilter']     = $valueposition;







		echo json_encode($data);

	}







	//API GET Form Edit Data User



	public function data_user_formedit_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['teknisiId'] 				= TeknisiModel::find($id);











		$array['data'][] = [



			'name' => $data['teknisiId']['name'],



			'code_name' => $data['teknisiId']['code_name'],



			'username' => $data['teknisiId']['username'],



			'email' => $data['teknisiId']['email'],



			'no_tlp' => $data['teknisiId']['no_tlp'],



			'no_tlp2' => $data['teknisiId']['no_tlp2'],



			'ekstensi' => $data['teknisiId']['ekstensi'],



			'id_posisi' => $data['teknisiId']['position']['id'],



			'posisi' => $data['teknisiId']['position']['name'],



			'id_jabatan' => $data['teknisiId']['jabatan']['id'],



			'jabatan' => $data['teknisiId']['jabatan']['name'],



			'status_sales' => $data['teknisiId']['status_sales'],



			'status_user' => $data['teknisiId']['status_regis'],







			'id_sales' => $data['teknisiId']['id_sales']



		];



		// $array['data'][]['code_name'] = $data['teknisiId']['code_name'];



		echo json_encode($array);

	}







	//API POST Tambah Data User



	public function data_user_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name'], ['username'], ['code_name'], ['email'], ['password_name'], ['tlp'], ['tlp2'], ['ekstensi'], ['jabatan'], ['position'], ['status'], ['status_sales']



			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$response = array(



				'error' => 'Gagal lolos validasi',



				'msg' => $validate



			);



			echo json_encode($response);







			return;

		}







		$id_position 						= $this->input->post('position');



		// dd($id_position);



		$position 							= PositionKaryawanModel::find($id_position);







		if (!$position) {







			echo goResult(false, "Posisi karyawan tidak ada");







			return;

		}















		$id_jabatan 						= $this->input->post('jabatan');



		// dd($id_jabatan);



		$jabatan 							= JabatanModel::find($id_jabatan);







		if (!$jabatan) {







			echo goResult(false, "Rule tidak ada");







			return;

		}







		$teknisiNew 						= new TeknisiModel;







		$teknisiNew->id_bee 				= $this->input->post('id_bee');







		$teknisiNew->id_jabatan 			= $id_jabatan;







		$teknisiNew->id_position 			= $id_position;







		$teknisiNew->name 					= $this->input->post('name');







		$teknisiNew->code_name 				= $this->input->post('code_name');







		$teknisiNew->username 				= $this->input->post('username');







		$teknisiNew->email 					= $this->input->post('email');







		$teknisiNew->no_tlp 				= $this->input->post('tlp');







		$teknisiNew->no_tlp2 				= $this->input->post('tlp2');







		$teknisiNew->ekstensi 				= $this->input->post('ekstensi');







		$teknisiNew->status_regis 			= $this->input->post('status');







		$teknisiNew->password 				= DefuseLib::encrypt($teknisiNew->password_name);







		$teknisiNew->password_name 			= $this->input->post('password_name');;







		$teknisiNew->status_profile 		= 1;







		$teknisiNew->status_sales 			= $this->input->post('status_sales');















		$statususer 						= seo($jabatan->name);







		$statusexplode 						= explode('-', $statususer);







		if (count($statusexplode) > 0) {







			$teknisiNew->status 			= str_replace('-', '_', $statususer);

		} else {







			$teknisiNew->status 			= $statususer;

		}















		if ($teknisiNew->save()) {







			echo json_encode('User berhasil di tambah');







			return;

		} else {







			echo json_encode('User gagal di tambah');







			return;

		}

	}







	//API PUT Data User



	public function data_user_edit_put()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$input_data = json_decode(trim(file_get_contents('php://input')), true);



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			// Mengambil data dari body permintaan PUT Postman



			$id_position = isset($input_data['position']) ? $input_data['position'] : null;



			// dd($id_position);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$id_jabatan                         = isset($input_data['jabatan']) ? $input_data['jabatan'] : null;

		} else {







			$id_position = $this->input->post('position');



			$id = $this->input->post('id');



			$id = $this->input->post('jabatan');

		}







		$position                           = PositionKaryawanModel::find($id_position);











		if (!$position) {



			$msg['status'] = false;



			$msg['msg'] = 'Posisi karyawan tidak ada';



			echo json_encode($msg);







			return;

		}











		$jabatan = JabatanModel::find($id_jabatan);







		if (!$jabatan) {







			$msg['status'] = false;



			$msg['msg'] = 'Jabatan karyawan tidak ada';



			echo json_encode($msg);







			return;

		}







		$teknisiNew 						= TeknisiModel::find($id);



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {











			$teknisiNew->id_bee 				= isset($input_data['id_bee']) ? $input_data['id_bee'] : null;



			$teknisiNew->id_jabatan 			= $id_jabatan;







			$teknisiNew->id_position 			= $id_position;







			$teknisiNew->name 			= isset($input_data['name']) ? $input_data['name'] : null;











			$teknisiNew->code_name 				= isset($input_data['code_name']) ? $input_data['code_name'] : null;;







			$teknisiNew->username 				= isset($input_data['username']) ? $input_data['username'] : null;;







			$teknisiNew->email 					= isset($input_data['email']) ? $input_data['email'] : null;;







			$teknisiNew->no_tlp 				= isset($input_data['tlp']) ? $input_data['tlp'] : null;;







			$teknisiNew->no_tlp2 				= isset($input_data['tlp2']) ? $input_data['tlp2'] : null;;







			$teknisiNew->ekstensi 				= isset($input_data['ekstensi']) ? $input_data['ekstensi'] : null;;







			$teknisiNew->status_regis 			= isset($input_data['status']) ? $input_data['status'] : null;;







			$teknisiNew->status_sales 			= isset($input_data['status_sales']) ? $input_data['status_sales'] : null;;







			$statususer 						= seo($jabatan->name);



			$statusexplode 						= explode('-', $statususer);











			// $rules = [







			//     'required'  => [







			//         ['name'],['username'],['email'],['tlp'],['ekstensi'],['jabatan'],['position'],['status'],['status_sales']







			//     ]







			// ];



			// // dd($rules);



			// $validate 	= Validation::check($rules,'post');







			//    if(!$validate->auth){







			// 		echo json_encode($validate);







			// 		return;







			// 	}











			if (count($statusexplode) > 0) {







				$teknisiNew->status 			= str_replace('-', '_', $statususer);

			} else {







				$teknisiNew->status 			= $statususer;

			}







			if ($teknisiNew->save()) {











				echo json_encode('User Berhasil di edit');







				return;

			} else {



				echo json_encode('User Tidak Berhasil di edit');







				return;

			}

		} else {







			$teknisiNew->id_bee 				= $this->input->post('id_bee');



			$teknisiNew->id_jabatan 			= $id_jabatan;







			$teknisiNew->id_position 			= $id_position;







			$teknisiNew->name 					= $this->input->post('name');







			$teknisiNew->code_name 				= $this->input->post('code_name');







			$teknisiNew->username 				= $this->input->post('username');







			$teknisiNew->email 					= $this->input->post('email');







			$teknisiNew->no_tlp 				= $this->input->post('tlp');







			$teknisiNew->no_tlp2 				= $this->input->post('tlp2');







			$teknisiNew->ekstensi 				= $this->input->post('ekstensi');







			$teknisiNew->status_regis 			= $this->input->post('status');







			$teknisiNew->status_sales 			= $this->input->post('status_sales');







			$statususer 						= seo($jabatan->name);







			$statusexplode 						= explode('-', $statususer);







			$rules = [







				'required' 	=> [







					['name'], ['username'], ['code_name'], ['email'], ['tlp'], ['tlp2'], ['ekstensi'], ['jabatan'], ['position'], ['status'], ['status_sales']



				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {



				$response = array(



					'error' => 'Gagal lolos validasi',



					'msg' => $validate



				);



				echo json_encode($response);







				return;

			}







			if (count($statusexplode) > 0) {







				$teknisiNew->status 			= str_replace('-', '_', $statususer);

			} else {







				$teknisiNew->status 			= $statususer;

			}















			if ($teknisiNew->save()) {











				echo json_encode('User Berhasil di edit');







				return;

			} else {







				echo json_encode('User Tidak Berhasil di edit');







				return;

			}

		}

	}







	//API Rule User



	public function rule_user_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		// echo json_encode("sudah login");



		$nameFilter              = $this->input->get('name');



		if (!$nameFilter) {







			$valuename              = '';

		} else {







			$valuename              = $nameFilter;

		}







		$rule                      = JabatanModel::where('name', 'like', '%' . $valuename . '%')->orderBy('name', 'asc')->get();















		$page                       = $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page                   = 0;

		}



		$total                       = count($rule);







		$data['msg'] = 'success';



		$data['auth'] = true;



		$data['rule']               = JabatanModel::where('name', 'like', '%' . $valuename . '%')->orderBy('name', 'asc')->get();



		$data['nameFilter']         = $valuename;







		echo json_encode($data);

	}







	//API Form Edit Rule User



	public function rule_user_formedit_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$rule 							= JabatanModel::find($id);



		// dd($rule);



		$data['rule'] = $rule;



		echo json_encode($data);

	}







	//API Tambah Rule User



	public function rule_user_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules 		= [







			'required' 	=> [







				['name']







			]







		];



		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$response = array(



				'error' => 'Gagal lolos validasi',



				'msg' => $validate



			);



			echo json_encode($response);







			return;

		}



		$jabatan 								= new JabatanModel;



		$jabatan->name 							= $this->input->post('name');







		$jabatan->m_service 					= $this->input->post('service');







		$jabatan->m_daftarservice 				= $this->input->post('daftarservice');







		$jabatan->m_allservice 					= $this->input->post('allservice');







		$jabatan->m_addinvoiceservice 			= $this->input->post('addinvoiceservice');







		$jabatan->m_pendingservice 				= '0';







		$jabatan->m_pendingserviceproses 		= '0';







		$jabatan->m_pendingsync 				= '0';







		$jabatan->m_pelaporan 					= $this->input->post('pelaporan');







		$jabatan->m_tambahpelaporan 			= $this->input->post('tambahpelaporan');







		$jabatan->m_editpelaporan 				= $this->input->post('editpelaporan');







		$jabatan->m_hapuspelaporan 				= $this->input->post('hapuspelaporan');







		$jabatan->m_spkpelaporan 				= $this->input->post('spkpelaporan');







		$jabatan->m_biayapelaporan 				= '0';







		$jabatan->m_detailpelaporan 			= $this->input->post('detailpelaporan');







		$jabatan->m_syncinvoiceservice 			= $this->input->post('syncinvoiceservice');







		$jabatan->m_pengerjaan 					= $this->input->post('pengerjaan');







		$jabatan->m_printpengerjaan 			= $this->input->post('printpengerjaan');







		$jabatan->m_hapuspengerjaan 			= $this->input->post('hapuspengerjaan');







		$jabatan->m_donepengerjaan 				= $this->input->post('donepengerjaan');







		$jabatan->m_selesai 					= $this->input->post('selesai');







		$jabatan->m_hapusselesai 				= $this->input->post('hapusselesai');







		$jabatan->m_detailselesai 				= $this->input->post('detailselesai');







		$jabatan->m_doneselesai 				= '0';







		$jabatan->m_100selesai 					= $this->input->post('100selesai');







		$jabatan->m_nota100selesai 				= '0';







		$jabatan->m_detail100selesai 			= $this->input->post('detail100selesai');







		$jabatan->m_daftartagihan 				= '0';







		$jabatan->m_alltagihan 					= '0';







		$jabatan->m_exportalltagihan 			= '0';







		$jabatan->m_pelunasanalltagihan 		= '0';







		$jabatan->m_bayartagihan 				= '0';







		$jabatan->m_exportbayartagihan 			= '0';







		$jabatan->m_pelunasanbayartagihan 		= '0';







		$jabatan->m_lunastagihan 				= '0';







		$jabatan->m_exportlunastagihan 			= '0';







		$jabatan->m_daftarpembayaran 			= '0';







		$jabatan->m_tambahpembayaran 			= '0';







		$jabatan->m_editpembayaran 				= '0';







		$jabatan->m_detailpembayaran 			= '0';







		$jabatan->m_hapuspembayaran 			= '0';







		$jabatan->m_laporanservice 				= $this->input->post('laporanservice');







		$jabatan->m_totallaporan 				= $this->input->post('totallaporan');







		$jabatan->m_exporttotallaporan 			= $this->input->post('exporttotallaporan');







		$jabatan->m_laporanbymesin 				= $this->input->post('laporanbymesin');







		$jabatan->m_exportlaporanbymesin 		= $this->input->post('exportlaporanbymesin');







		$jabatan->m_detaillaporanbymesin 		= $this->input->post('detaillaporanbymesin');







		$jabatan->m_laporanbycustomer 			= $this->input->post('laporanbycustomer');







		$jabatan->m_exportlaporanbycustomer 	= $this->input->post('exportlaporanbycustomer');







		$jabatan->m_detaillaporanbycustomer 	= $this->input->post('detaillaporanbycustomer');







		$jabatan->m_laporanbyteknisi 			= $this->input->post('laporanbyteknisi');







		$jabatan->m_exportlaporanbyteknisi 		= $this->input->post('exportlaporanbyteknisi');







		$jabatan->m_detaillaporanbyteknisi 		= $this->input->post('detaillaporanbyteknisi');







		$jabatan->m_laporantunjangan 			= $this->input->post('laporantunjangan');







		$jabatan->m_exportlaporantunjangan 		= $this->input->post('exportlaporantunjangan');







		$jabatan->m_detaillaporantunjangan 		= $this->input->post('detaillaporantunjangan');







		$jabatan->m_tunjanganglobal 			= $this->input->post('tunjanganglobal');







		$jabatan->m_account 					= $this->input->post('account');







		$jabatan->m_tambahaccount 				= $this->input->post('tambahaccount');







		$jabatan->m_editaccount 				= $this->input->post('editaccount');







		$jabatan->m_rule 						= $this->input->post('rule');







		$jabatan->m_tambahrule 					= $this->input->post('tambahrule');







		$jabatan->m_editrule 					= $this->input->post('editrule');







		$jabatan->m_detailrule 					= $this->input->post('detailrule');







		$jabatan->m_hapusrule 					= $this->input->post('hapusrule');







		$jabatan->m_positionkaryawan 			= $this->input->post('positionkaryawan');







		$jabatan->m_tambahpositionkaryawan 		= $this->input->post('tambahpositionkaryawan');







		$jabatan->m_editpositionkaryawan 		= $this->input->post('editpositionkaryawan');







		$jabatan->m_detailpositionkaryawan 		= $this->input->post('detailpositionkaryawan');







		$jabatan->m_penawaran 					= $this->input->post('penawaran');







		$jabatan->m_tambahpenawaran 			= $this->input->post('tambahpenawaran');







		$jabatan->m_editpenawaran 				= $this->input->post('editpenawaran');







		$jabatan->m_hapuspenawaran 				= $this->input->post('hapuspenawaran');







		$jabatan->m_detailpenawaran 			= $this->input->post('detailpenawaran');







		$jabatan->m_printpdfpenawaran 			= $this->input->post('printpdfpenawaran');







		$jabatan->m_sendemailpenawaran 			= '0';







		$jabatan->m_menuproduk 					= $this->input->post('menuproduk');







		$jabatan->m_produk 						= $this->input->post('produk');







		$jabatan->m_tambahproduk 				= $this->input->post('tambahproduk');







		$jabatan->m_editproduk 					= $this->input->post('editproduk');







		$jabatan->m_hapusproduk 				= $this->input->post('hapusproduk');







		$jabatan->m_detailproduk 				= $this->input->post('detailproduk');







		$jabatan->m_templateproduk 				= $this->input->post('templateproduk');







		$jabatan->m_importproduk 				= $this->input->post('importproduk');







		$jabatan->m_exportrakproduk 			= $this->input->post('exportrakproduk');







		$jabatan->m_cekcustomerproduk 			= $this->input->post('cekcustomerproduk');







		$jabatan->m_clearcheckproduk 			= $this->input->post('clearcheckproduk');







		$jabatan->m_produkrusak 				= $this->input->post('produkrusak');







		$jabatan->m_tambahprodukrusak 			= $this->input->post('tambahprodukrusak');







		$jabatan->m_editprodukrusak 			= $this->input->post('editprodukrusak');







		$jabatan->m_hapusprodukrusak 			= $this->input->post('hapusprodukrusak');







		$jabatan->m_checkprodukrusak 			= $this->input->post('checkprodukrusak');







		$jabatan->m_customer 					= $this->input->post('customer');







		$jabatan->m_customervip 				= '0';







		$jabatan->m_buttoncustomervip 			= $this->input->post('buttoncustomervip');







		$jabatan->m_customerbaru 				= $this->input->post('customerbaru');







		$jabatan->m_customerqontak 				= $this->input->post('customerqontak');







		$jabatan->m_editcustomerqontak 			= $this->input->post('editcustomerqontak');







		$jabatan->m_hapuscustomerqontak 		= $this->input->post('hapuscustomerqontak');







		$jabatan->m_customerwebsite 			= $this->input->post('customerwebsite');







		$jabatan->m_editcustomerwebsite 		= $this->input->post('editcustomerwebsite');







		$jabatan->m_hapuscustomerwebsite 		= $this->input->post('hapuscustomerwebsite');







		$jabatan->m_customergoogleads 			= $this->input->post('customergoogleads');







		$jabatan->m_editcustomergoogleads 		= $this->input->post('editcustomergoogleads');







		$jabatan->m_hapuscustomergoogleads 		= $this->input->post('hapuscustomergoogleads');







		$jabatan->m_tambahcustomerbaru 			= $this->input->post('tambahcustomerbaru');







		$jabatan->m_editcustomerbaru 			= $this->input->post('editcustomerbaru');







		$jabatan->m_hapuscustomerbaru 			= $this->input->post('hapuscustomerbaru');







		$jabatan->m_customerleads 				= $this->input->post('customerleads');







		$jabatan->m_tambahcustomerleads 		= $this->input->post('tambahcustomerleads');







		$jabatan->m_editcustomerleads 			= $this->input->post('editcustomerleads');







		$jabatan->m_hapuscustomerleads 			= $this->input->post('hapuscustomerleads');







		$jabatan->m_movecustomerleads 			= '0';







		$jabatan->m_mergecustomerleads 			= $this->input->post('mergecustomerleads');







		$jabatan->m_masterkaskeluar 			= $this->input->post('masterkaskeluar');







		$jabatan->m_laporankaskeluar 			= $this->input->post('laporankaskeluar');







		$jabatan->m_laporanexportkaskeluar 		= $this->input->post('laporanexportkaskeluar');







		$jabatan->m_laporanexportxlskaskeluar 	= $this->input->post('laporanexportxlskaskeluar');







		$jabatan->m_kaskeluar 					= $this->input->post('kaskeluar');







		$jabatan->m_tambahkaskeluar 			= $this->input->post('tambahkaskeluar');







		$jabatan->m_editkaskeluar 				= $this->input->post('editkaskeluar');







		$jabatan->m_hapuskaskeluar 				= $this->input->post('hapuskaskeluar');







		$jabatan->m_invoicekaskeluar 			= $this->input->post('invoicekaskeluar');







		$jabatan->m_coakaskeluar 				= $this->input->post('coakaskeluar');







		$jabatan->m_exportpdfkaskeluar 			= $this->input->post('exportpdfkaskeluar');







		$jabatan->m_exportxlskaskeluar 			= $this->input->post('exportxlskaskeluar');







		$jabatan->m_master 						= $this->input->post('master');







		$jabatan->m_discount 					= $this->input->post('discount');







		$jabatan->m_loker 						= $this->input->post('loker');







		$jabatan->m_browseloker 				= $this->input->post('browseloker');







		$jabatan->m_checkstarloker 				= $this->input->post('checkstarloker');







		$jabatan->m_removestarloker 			= $this->input->post('removestarloker');







		$jabatan->m_viewpdfloker 				= $this->input->post('viewpdfloker');







		$jabatan->m_detailloker 				= $this->input->post('detailloker');







		$jabatan->m_hapusloker 					= $this->input->post('hapusloker');







		$jabatan->m_penjualan 					= $this->input->post('penjualan');







		$jabatan->m_tambahpenjualan 			= $this->input->post('tambahpenjualan');







		$jabatan->m_editpenjualan 				= $this->input->post('editpenjualan');







		$jabatan->m_menupenjualan 				= $this->input->post('menupenjualan');







		$jabatan->m_suratjalanpenjualan 		= $this->input->post('suratjalanpenjualan');







		$jabatan->m_qrcodepenjualan 			= '0';







		$jabatan->m_printpenjualan 				= '0';







		$jabatan->m_viewpenjualan 				= $this->input->post('viewpenjualan');







		$jabatan->m_hapuspenjualan 				= $this->input->post('hapuspenjualan');







		$jabatan->m_kirimpenjualan 				= '0';







		$jabatan->m_checklistpenjualan 			= $this->input->post('checklistpenjualan');







		$jabatan->m_printsuratpenjualan 		= $this->input->post('printsuratpenjualan');







		$jabatan->m_printinvoicepenjualan 		= $this->input->post('printinvoicepenjualan');







		$jabatan->m_voidpenjualan 				= $this->input->post('voidpenjualan');







		$jabatan->m_hapusinvoicepenjualan 		= $this->input->post('hapusinvoicepenjualan');







		$jabatan->m_customerlabel 				= $this->input->post('customerlabel');







		$jabatan->m_customerexport 				= $this->input->post('customerexport');







		$jabatan->m_customeraudience 			= $this->input->post('customeraudience');







		$jabatan->m_exportproduk 				= $this->input->post('exportproduk');







		$jabatan->m_pricelistproduk 			= $this->input->post('pricelistproduk');







		$jabatan->m_waqiscus 					= $this->input->post('waqiscus');







		$jabatan->m_retrywaqiscus 				= $this->input->post('retrywaqiscus');







		$jabatan->m_wapancake 					= $this->input->post('wapancake');







		$jabatan->m_ekspedisi 					= $this->input->post('ekspedisi');







		$jabatan->m_tambahekspedisi 			= $this->input->post('tambahekspedisi');







		$jabatan->m_editekspedisi 				= $this->input->post('editekspedisi');







		$jabatan->m_hapusekspedisi 				= $this->input->post('hapusekspedisi');







		$jabatan->m_coa 						= $this->input->post('coa');







		$jabatan->m_tambahcoa 					= $this->input->post('tambahcoa');







		$jabatan->m_editcoa 					= $this->input->post('editcoa');







		$jabatan->m_hapuscoa 					= $this->input->post('hapuscoa');







		$jabatan->m_exportcoa 					= $this->input->post('exportcoa');







		$jabatan->m_klasifikasi 				= $this->input->post('klasifikasi');







		$jabatan->m_tambahklasifikasi 			= $this->input->post('tambahklasifikasi');







		$jabatan->m_editklasifikasi 			= $this->input->post('editklasifikasi');







		$jabatan->m_hapusklasifikasi 			= $this->input->post('hapusklasifikasi');







		$jabatan->m_commercialinvoice 			= $this->input->post('commercialinvoice');







		$jabatan->m_tambahcommercialinvoice 	= $this->input->post('tambahcommercialinvoice');







		$jabatan->m_editcommercialinvoice 		= $this->input->post('editcommercialinvoice');







		$jabatan->m_prosescommercialinvoice 	= '0';







		$jabatan->m_detailcommercialinvoice 	= $this->input->post('detailcommercialinvoice');







		$jabatan->m_hapuscommercialinvoice 		= $this->input->post('hapuscommercialinvoice');







		$jabatan->m_fclcontainer 				= $this->input->post('fclcontainer');







		$jabatan->m_editfclcontainer 			= $this->input->post('editfclcontainer');







		$jabatan->m_tambahfclcontainer 			= $this->input->post('tambahfclcontainer');







		$jabatan->m_detailfclcontainer 			= $this->input->post('detailfclcontainer');







		$jabatan->m_hapusfclcontainer 			= $this->input->post('hapusfclcontainer');







		$jabatan->m_dailyactivities 			= $this->input->post('dailyactivities');







		$jabatan->m_tambahdailyactivities 		= $this->input->post('tambahdailyactivities');







		$jabatan->m_gudang 						= $this->input->post('gudang');







		$jabatan->m_tambahgudang 				= $this->input->post('tambahgudang');







		$jabatan->m_editgudang 					= $this->input->post('editgudang');







		$jabatan->m_hapusgudang 				= $this->input->post('hapusgudang');







		$jabatan->m_rak 						= $this->input->post('rak');







		$jabatan->m_tambahrak 					= $this->input->post('tambahrak');







		$jabatan->m_editrak 					= $this->input->post('editrak');







		$jabatan->m_hapusrak 					= $this->input->post('hapusrak');







		$jabatan->m_menusupplier 				= $this->input->post('menusupplier');







		$jabatan->m_supplier 					= $this->input->post('supplier');







		$jabatan->m_tambahsupplier 				= $this->input->post('tambahsupplier');







		$jabatan->m_editsupplier 				= $this->input->post('editsupplier');







		$jabatan->m_hapussupplier 				= $this->input->post('hapussupplier');







		$jabatan->m_supplierbank 				= $this->input->post('supplierbank');







		$jabatan->m_tambahsupplierbank 			= $this->input->post('tambahsupplierbank');







		$jabatan->m_editsupplierbank 			= $this->input->post('editsupplierbank');







		$jabatan->m_hapussupplierbank 			= $this->input->post('hapussupplierbank');







		$jabatan->m_orderpenjualan 				= $this->input->post('orderpenjualan');







		$jabatan->m_printsuratjalanorderpenjualan 	= $this->input->post('printsuratjalanorderpenjualan');







		$jabatan->m_printinvoiceorderpenjualan 		= $this->input->post('printinvoiceorderpenjualan');







		$jabatan->m_printinvoicepdforderpenjualan 	= $this->input->post('printinvoicepdforderpenjualan');







		$jabatan->m_detailorderpenjualan 			= $this->input->post('detailorderpenjualan');







		$jabatan->m_hapusorderpenjualan 			= $this->input->post('hapusorderpenjualan');







		$jabatan->m_editorderpenjualan 				= $this->input->post('editorderpenjualan');







		$jabatan->m_tambahorderpenjualan 			= $this->input->post('tambahorderpenjualan');







		$jabatan->m_pembelian 					= $this->input->post('pembelian');







		$jabatan->m_restok 						= $this->input->post('restok');







		$jabatan->m_tambahrestok 				= $this->input->post('tambahrestok');







		$jabatan->m_editrestok 					= $this->input->post('editrestok');







		$jabatan->m_hapusrestok 				= $this->input->post('hapusrestok');







		$jabatan->m_filterrestok 				= '0';







		$jabatan->m_orderpembelian 				= $this->input->post('orderpembelian');







		$jabatan->m_filterorderpembelian 		= '0';







		$jabatan->m_prosesorderpembelian 		= '0';







		$jabatan->m_editorderpembelian 			= $this->input->post('editorderpembelian');







		$jabatan->m_rejectorderpembelian 		= $this->input->post('rejectorderpembelian');







		$jabatan->m_undoorderpembelian 			= $this->input->post('undoorderpembelian');







		$jabatan->m_copylinkorderpembelian 		= '0';







		$jabatan->m_hapusorderpembelian 		= $this->input->post('hapusorderpembelian');







		$jabatan->m_stok 						= $this->input->post('stok');







		$jabatan->m_lihatstok 					= $this->input->post('lihatstok');







		$jabatan->m_opnamestok 					= $this->input->post('opnamestok');







		$jabatan->m_quotes 						= $this->input->post('quotes');







		$jabatan->m_tambahquotes 				= $this->input->post('tambahquotes');







		$jabatan->m_editquotes 					= $this->input->post('editquotes');







		$jabatan->m_hapusquotes 				= $this->input->post('hapusquotes');







		$jabatan->m_pengiriman 					= $this->input->post('pengiriman');







		$jabatan->m_penjualanpengiriman 		= $this->input->post('penjualanpengiriman');







		$jabatan->m_editpenjualanpengiriman 		= $this->input->post('editpenjualanpengiriman');







		$jabatan->m_detailpenjualanpengiriman 		= $this->input->post('detailpenjualanpengiriman');







		$jabatan->m_printpenjualanpengiriman 		= $this->input->post('printpenjualanpengiriman');







		$jabatan->m_kirimsuratpenjualanpengiriman 	= $this->input->post('kirimsuratpenjualanpengiriman');







		$jabatan->m_pindahgudangpengiriman 		= $this->input->post('pindahgudangpengiriman');







		$jabatan->m_tambahpindahgudang 			= $this->input->post('tambahpindahgudang');







		$jabatan->m_editpindahgudang 			= $this->input->post('editpindahgudang');







		$jabatan->m_suratjalanpindahgudang 		= $this->input->post('suratjalanpindahgudang');







		$jabatan->m_terimapindahgudang 			= '0';







		$jabatan->m_hapussuratpindahgudang 		= $this->input->post('hapussuratpindahgudang');







		$jabatan->m_detailpindahgudang 			= $this->input->post('detailpindahgudang');







		$jabatan->m_hapuspindahgudang 			= $this->input->post('hapuspindahgudang');







		$jabatan->m_printpindahgudang 			= $this->input->post('printpindahgudang');







		$jabatan->m_broadcast 					= '0';







		$jabatan->m_tambahbroadcast 			= '0';







		$jabatan->m_dokumen 					= '0';







		$jabatan->m_tambahdokumen 				= '0';







		$jabatan->m_editdokumen 				= '0';







		$jabatan->m_hapusdokumen 				= '0';







		$jabatan->m_detaildokumen 				= '0';







		$jabatan->m_survey 						= '0';







		$jabatan->m_menupenjualanoption 			= $this->input->post('menupenjualanoption');







		$jabatan->m_penjualanoptioncategory 		= $this->input->post('penjualanoptioncategory');







		$jabatan->m_tambahpenjualanoptioncategory 	= $this->input->post('tambahpenjualanoptioncategory');







		$jabatan->m_editpenjualanoptioncategory 	= $this->input->post('editpenjualanoptioncategory');







		$jabatan->m_hapuspenjualanoptioncategory 	= $this->input->post('hapuspenjualanoptioncategory');







		$jabatan->m_penjualanoption 			= $this->input->post('penjualanoption');







		$jabatan->m_tambahpenjualanoption 		= $this->input->post('tambahpenjualanoption');







		$jabatan->m_editpenjualanoption 		= $this->input->post('editpenjualanoption');







		$jabatan->m_hapuspenjualanoption 		= $this->input->post('hapuspenjualanoption');







		$jabatan->m_cabang 						= $this->input->post('cabang');







		$jabatan->m_tambahcabang 				= $this->input->post('tambahcabang');







		$jabatan->m_editcabang 					= $this->input->post('editcabang');







		$jabatan->m_hapuscabang 				= $this->input->post('hapuscabang');







		$jabatan->m_kategoriproduct 			= $this->input->post('kategoriproduct');







		$jabatan->m_tambahkategoriproduct 		= $this->input->post('tambahkategoriproduct');







		$jabatan->m_editkategoriproduct 		= $this->input->post('editkategoriproduct');







		$jabatan->m_hapuskategoriproduct 		= $this->input->post('hapuskategoriproduct');







		$jabatan->m_landingpage 				= $this->input->post('landingpage');







		$jabatan->m_tambahlandingpage 			= $this->input->post('tambahlandingpage');







		$jabatan->m_editlandingpage 			= $this->input->post('editlandingpage');







		$jabatan->m_hapuslandingpage 			= $this->input->post('hapuslandingpage');







		$jabatan->m_accountbank 				= $this->input->post('accountbank');







		$jabatan->m_tambahaccountbank 			= $this->input->post('tambahaccountbank');







		$jabatan->m_editaccountbank 			= $this->input->post('editaccountbank');







		$jabatan->m_hapusaccountbank 			= $this->input->post('hapusaccountbank');







		$jabatan->m_termin 						= $this->input->post('termin');







		$jabatan->m_tambahtermin 				= $this->input->post('tambahtermin');







		$jabatan->m_edittermin 					= $this->input->post('edittermin');







		$jabatan->m_hapustermin 				= $this->input->post('hapustermin');







		$jabatan->m_matauang 					= $this->input->post('matauang');







		$jabatan->m_tambahmatauang 				= $this->input->post('tambahmatauang');







		$jabatan->m_editmatauang 				= $this->input->post('editmatauang');







		$jabatan->m_hapusmatauang 				= $this->input->post('hapusmatauang');







		$jabatan->m_pembelianlcl 				= $this->input->post('pembelianlcl');







		$jabatan->m_tambahpembelianlcl 			= $this->input->post('tambahpembelianlcl');







		$jabatan->m_editpembelianlcl 			= $this->input->post('editpembelianlcl');







		$jabatan->m_hapuspembelianlcl 			= $this->input->post('hapuspembelianlcl');







		$jabatan->m_detailpembelianlcl 			= $this->input->post('detailpembelianlcl');







		$jabatan->m_laporananalisa 				= $this->input->post('laporananalisa');







		$jabatan->m_laporananalisaservice 		= $this->input->post('laporananalisaservice');







		$jabatan->m_laporananalisapenjualan 	= $this->input->post('laporananalisapenjualan');







		$jabatan->m_laporananalisapembelian 	= $this->input->post('laporananalisapembelian');







		$jabatan->m_laporananalisakaskeluar 	= $this->input->post('laporananalisakaskeluar');







		$jabatan->m_laporananalisacustomer 		= $this->input->post('laporananalisacustomer');







		$jabatan->m_laporananalisaregresi 		= $this->input->post('laporananalisaregresi');







		$jabatan->m_laporananalisaorderpenjualan 	= $this->input->post('laporananalisaorderpenjualan');







		$jabatan->m_laporananalisaanalytics 	= $this->input->post('laporananalisaanalytics');







		$jabatan->m_laporanmutasistok 			= $this->input->post('laporanmutasistok');







		$jabatan->m_itemgroup 					= $this->input->post('itemgroup');







		$jabatan->m_tambahitemgroup 			= $this->input->post('tambahitemgroup');







		$jabatan->m_edititemgroup 				= $this->input->post('edititemgroup');







		$jabatan->m_hapusitemgroup 				= $this->input->post('hapusitemgroup');







		$jabatan->m_penerimaan 					= $this->input->post('penerimaan');







		$jabatan->m_penerimaanpembelian 		= $this->input->post('penerimaanpembelian');







		$jabatan->m_tambahpenerimaanpembelian 	= $this->input->post('tambahpenerimaanpembelian');







		$jabatan->m_editpenerimaanpembelian 	= $this->input->post('editpenerimaanpembelian');







		$jabatan->m_hapuspenerimaanpembelian 	= $this->input->post('hapuspenerimaanpembelian');







		$jabatan->m_detailpenerimaanpembelian 	= $this->input->post('detailpenerimaanpembelian');







		$jabatan->m_invoicepenerimaanpembelian 	= $this->input->post('invoicepenerimaanpembelian');







		$jabatan->m_kodedetailpenerimaanpembelian 	= $this->input->post('kodedetailpenerimaanpembelian');







		$jabatan->m_penerimaanpindahgudang 		= $this->input->post('penerimaanpindahgudang');







		$jabatan->m_tambahpenerimaanpindahgudang = $this->input->post('tambahpenerimaanpindahgudang');







		$jabatan->m_editpenerimaanpindahgudang 	= $this->input->post('editpenerimaanpindahgudang');







		$jabatan->m_hapuspenerimaanpindahgudang = $this->input->post('hapuspenerimaanpindahgudang');







		$jabatan->m_penerimaanbaranglain 		= $this->input->post('penerimaanbaranglain');







		$jabatan->m_tambahpenerimaanbaranglain 	= $this->input->post('tambahpenerimaanbaranglain');







		$jabatan->m_editpenerimaanbaranglain 	= $this->input->post('editpenerimaanbaranglain');







		$jabatan->m_hapuspenerimaanbaranglain 	= $this->input->post('hapuspenerimaanbaranglain');







		$jabatan->m_printpenerimaanbaranglain 	= $this->input->post('printpenerimaanbaranglain');







		$jabatan->m_penerimaandokumen 			= $this->input->post('penerimaandokumen');







		$jabatan->m_tambahpenerimaandokumen 	= $this->input->post('tambahpenerimaandokumen');







		$jabatan->m_editpenerimaandokumen 		= $this->input->post('editpenerimaandokumen');







		$jabatan->m_hapuspenerimaandokumen 		= $this->input->post('hapuspenerimaandokumen');







		$jabatan->m_printpenerimaandokumen 		= $this->input->post('printpenerimaandokumen');







		$jabatan->m_subkategoriproduct 			= $this->input->post('subkategoriproduct');







		$jabatan->m_tambahsubkategoriproduct 	= $this->input->post('tambahsubkategoriproduct');







		$jabatan->m_editsubkategoriproduct 		= $this->input->post('editsubkategoriproduct');







		$jabatan->m_hapussubkategoriproduct 	= $this->input->post('hapussubkategoriproduct');







		$jabatan->m_produkmoq 					= $this->input->post('produkmoq');







		$jabatan->m_tambahprodukmoq 			= $this->input->post('tambahprodukmoq');







		$jabatan->m_editprodukmoq 				= $this->input->post('editprodukmoq');







		$jabatan->m_hapusprodukmoq 				= $this->input->post('hapusprodukmoq');







		$jabatan->m_voucher 					= $this->input->post('voucher');







		$jabatan->m_datavoucher 				= $this->input->post('datavoucher');







		$jabatan->m_tambahvoucher 				= $this->input->post('tambahvoucher');







		$jabatan->m_editvoucher 				= $this->input->post('editvoucher');







		$jabatan->m_hapusvoucher 				= $this->input->post('hapusvoucher');







		$jabatan->m_vouchercustomer 			= $this->input->post('vouchercustomer');







		$jabatan->m_hapusvouchercustomer 		= $this->input->post('hapusvouchercustomer');







		$jabatan->m_sync 						= $this->input->post('sync');







		$jabatan->m_penjualansync 				= $this->input->post('penjualansync');







		$jabatan->m_orderpenjualansync 			= $this->input->post('orderpenjualansync');







		$jabatan->m_servicesync 				= $this->input->post('servicesync');







		$jabatan->m_pembayaran 					= $this->input->post('pembayaran');







		$jabatan->m_penjualanpembayaran 		= $this->input->post('penjualanpembayaran');







		$jabatan->m_editpenjualanpembayaran 	= $this->input->post('editpenjualanpembayaran');







		$jabatan->m_detailpenjualanpembayaran 	= $this->input->post('detailpenjualanpembayaran');







		$jabatan->m_uploadpenjualanpembayaran 	= $this->input->post('uploadpenjualanpembayaran');







		$jabatan->m_hapusuploadpenjualanpembayaran 	= $this->input->post('hapusuploadpenjualanpembayaran');







		$jabatan->m_listsparepart 				= $this->input->post('listsparepart');







		$jabatan->m_tambahlistsparepart 		= $this->input->post('tambahlistsparepart');







		$jabatan->m_cancellistsparepart 		= $this->input->post('cancellistsparepart');







		$jabatan->m_hapuslistsparepart 			= $this->input->post('hapuslistsparepart');







		$jabatan->m_detaillistsparepart         = $this->input->post('detaillistsparepart');







		$jabatan->m_menureview                  = $this->input->post('menureview');







		$jabatan->m_reviewservice               = $this->input->post('reviewservice');







		$jabatan->m_reviewservicesend           = $this->input->post('reviewservicesend');







		$jabatan->m_reviewservicevalue          = $this->input->post('reviewservicevalue');







		$jabatan->m_reviewpenjualan             = $this->input->post('reviewpenjualan');







		$jabatan->m_reviewpenjualansend         = $this->input->post('reviewpenjualansend');







		$jabatan->m_reviewpenjualanvalue 		= $this->input->post('reviewpenjualanvalue');







		$jabatan->m_penjualanboard 				= $this->input->post('penjualanboard');







		$jabatan->m_tambahpenjualanboard 		= $this->input->post('tambahpenjualanboard');







		$jabatan->m_editpenjualanboard 			= $this->input->post('editpenjualanboard');







		$jabatan->m_hapuspenjualanboard 		= $this->input->post('hapuspenjualanboard');







		$jabatan->m_lembur 						= '0';







		$jabatan->m_alarmlembur 				= '0';







		$jabatan->m_masterppn 					= $this->input->post('masterppn');







		$jabatan->m_masterrmbtousd 				= $this->input->post('masterrmbtousd');







		$jabatan->m_flyer 						= $this->input->post('flyer');







		$jabatan->m_tambahflyer 				= $this->input->post('tambahflyer');







		$jabatan->m_editflyer 					= $this->input->post('editflyer');







		$jabatan->m_hapusflyer 					= $this->input->post('hapusflyer');







		$jabatan->m_servicereimbursement 		= $this->input->post('servicereimbursement');







		$jabatan->m_tambahservicereimbursement 	= $this->input->post('tambahservicereimbursement');







		$jabatan->m_editservicereimbursement 	= $this->input->post('editservicereimbursement');







		$jabatan->m_hapusservicereimbursement 	= $this->input->post('hapusservicereimbursement');







		$jabatan->m_printservicereimbursement 	= $this->input->post('printservicereimbursement');







		$jabatan->m_peminjamanorderpenjualan 	= $this->input->post('peminjamanorderpenjualan');







		$jabatan->m_hapuspeminjamanorderpenjualan 			= $this->input->post('hapuspeminjamanorderpenjualan');







		$jabatan->m_pengembalianpeminjamanorderpenjualan 	= $this->input->post('pengembalianpeminjamanorderpenjualan');







		$jabatan->m_menupancake 				= $this->input->post('menupancake');







		$jabatan->m_pancake 					= $this->input->post('pancake');







		$jabatan->m_retrypancake 				= $this->input->post('retrypancake');







		$jabatan->m_pancaketemplate 			= $this->input->post('pancaketemplate');







		$jabatan->m_tambahpancaketemplate 		= $this->input->post('tambahpancaketemplate');







		$jabatan->m_editpancaketemplate 		= $this->input->post('editpancaketemplate');







		$jabatan->m_hapuspancaketemplate 		= $this->input->post('hapuspancaketemplate');







		$jabatan->m_statusorder 				= $this->input->post('statusorder');







		$jabatan->m_tambahstatusorder 			= $this->input->post('tambahstatusorder');







		$jabatan->m_editstatusorder 			= $this->input->post('editstatusorder');







		$jabatan->m_hapusstatusorder 			= $this->input->post('hapusstatusorder');











		if ($jabatan->save()) {







			echo json_encode("Jabatan berhasil di tambah");







			return;

		} else {







			echo json_encode("Jabatan gagal di tambah");







			return;

		}

	}







	//API PUT Rule User



	public function rule_user_edit_put()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules 		= [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		$id = $this->input->post('id');



		$jabatan 								= JabatanModel::find($id);











		$jabatan->name 							= $this->input->post('name');







		$jabatan->m_service 					= $this->input->post('service');







		$jabatan->m_daftarservice 				= $this->input->post('daftarservice');







		$jabatan->m_allservice 					= $this->input->post('allservice');







		$jabatan->m_addinvoiceservice 			= $this->input->post('addinvoiceservice');







		$jabatan->m_pendingservice 				= '0';







		$jabatan->m_pendingserviceproses 		= '0';







		$jabatan->m_pendingsync 				= '0';







		$jabatan->m_pelaporan 					= $this->input->post('pelaporan');







		$jabatan->m_tambahpelaporan 			= $this->input->post('tambahpelaporan');







		$jabatan->m_editpelaporan 				= $this->input->post('editpelaporan');







		$jabatan->m_hapuspelaporan 				= $this->input->post('hapuspelaporan');







		$jabatan->m_spkpelaporan 				= $this->input->post('spkpelaporan');







		$jabatan->m_biayapelaporan 				= '0';







		$jabatan->m_detailpelaporan 			= $this->input->post('detailpelaporan');







		$jabatan->m_syncinvoiceservice 			= $this->input->post('syncinvoiceservice');







		$jabatan->m_pengerjaan 					= $this->input->post('pengerjaan');







		$jabatan->m_printpengerjaan 			= $this->input->post('printpengerjaan');







		$jabatan->m_hapuspengerjaan 			= $this->input->post('hapuspengerjaan');







		$jabatan->m_donepengerjaan 				= $this->input->post('donepengerjaan');







		$jabatan->m_selesai 					= $this->input->post('selesai');







		$jabatan->m_hapusselesai 				= $this->input->post('hapusselesai');







		$jabatan->m_detailselesai 				= $this->input->post('detailselesai');







		$jabatan->m_doneselesai 				= '0';







		$jabatan->m_100selesai 					= $this->input->post('100selesai');







		$jabatan->m_nota100selesai 				= '0';







		$jabatan->m_detail100selesai 			= $this->input->post('detail100selesai');







		$jabatan->m_daftartagihan 				= '0';







		$jabatan->m_alltagihan 					= '0';







		$jabatan->m_exportalltagihan 			= '0';







		$jabatan->m_pelunasanalltagihan 		= '0';







		$jabatan->m_bayartagihan 				= '0';







		$jabatan->m_exportbayartagihan 			= '0';







		$jabatan->m_pelunasanbayartagihan 		= '0';







		$jabatan->m_lunastagihan 				= '0';







		$jabatan->m_exportlunastagihan 			= '0';







		$jabatan->m_daftarpembayaran 			= '0';







		$jabatan->m_tambahpembayaran 			= '0';







		$jabatan->m_editpembayaran 				= '0';







		$jabatan->m_detailpembayaran 			= '0';







		$jabatan->m_hapuspembayaran 			= '0';







		$jabatan->m_laporanservice 				= $this->input->post('laporanservice');







		$jabatan->m_totallaporan 				= $this->input->post('totallaporan');







		$jabatan->m_exporttotallaporan 			= $this->input->post('exporttotallaporan');







		$jabatan->m_laporanbymesin 				= $this->input->post('laporanbymesin');







		$jabatan->m_exportlaporanbymesin 		= $this->input->post('exportlaporanbymesin');







		$jabatan->m_detaillaporanbymesin 		= $this->input->post('detaillaporanbymesin');







		$jabatan->m_laporanbycustomer 			= $this->input->post('laporanbycustomer');







		$jabatan->m_exportlaporanbycustomer 	= $this->input->post('exportlaporanbycustomer');







		$jabatan->m_detaillaporanbycustomer 	= $this->input->post('detaillaporanbycustomer');







		$jabatan->m_laporanbyteknisi 			= $this->input->post('laporanbyteknisi');







		$jabatan->m_exportlaporanbyteknisi 		= $this->input->post('exportlaporanbyteknisi');







		$jabatan->m_detaillaporanbyteknisi 		= $this->input->post('detaillaporanbyteknisi');







		$jabatan->m_laporantunjangan 			= $this->input->post('laporantunjangan');







		$jabatan->m_exportlaporantunjangan 		= $this->input->post('exportlaporantunjangan');







		$jabatan->m_detaillaporantunjangan 		= $this->input->post('detaillaporantunjangan');







		$jabatan->m_tunjanganglobal 			= $this->input->post('tunjanganglobal');







		$jabatan->m_account 					= $this->input->post('account');







		$jabatan->m_tambahaccount 				= $this->input->post('tambahaccount');







		$jabatan->m_editaccount 				= $this->input->post('editaccount');







		$jabatan->m_rule 						= $this->input->post('rule');







		$jabatan->m_tambahrule 					= $this->input->post('tambahrule');







		$jabatan->m_editrule 					= $this->input->post('editrule');







		$jabatan->m_detailrule 					= $this->input->post('detailrule');







		$jabatan->m_hapusrule 					= $this->input->post('hapusrule');







		$jabatan->m_positionkaryawan 			= $this->input->post('positionkaryawan');







		$jabatan->m_tambahpositionkaryawan 		= $this->input->post('tambahpositionkaryawan');







		$jabatan->m_editpositionkaryawan 		= $this->input->post('editpositionkaryawan');







		$jabatan->m_detailpositionkaryawan 		= $this->input->post('detailpositionkaryawan');







		$jabatan->m_penawaran 					= $this->input->post('penawaran');







		$jabatan->m_tambahpenawaran 			= $this->input->post('tambahpenawaran');







		$jabatan->m_editpenawaran 				= $this->input->post('editpenawaran');







		$jabatan->m_hapuspenawaran 				= $this->input->post('hapuspenawaran');







		$jabatan->m_detailpenawaran 			= $this->input->post('detailpenawaran');







		$jabatan->m_printpdfpenawaran 			= $this->input->post('printpdfpenawaran');







		$jabatan->m_sendemailpenawaran 			= '0';







		$jabatan->m_menuproduk 					= $this->input->post('menuproduk');







		$jabatan->m_produk 						= $this->input->post('produk');







		$jabatan->m_tambahproduk 				= $this->input->post('tambahproduk');







		$jabatan->m_editproduk 					= $this->input->post('editproduk');







		$jabatan->m_hapusproduk 				= $this->input->post('hapusproduk');







		$jabatan->m_detailproduk 				= $this->input->post('detailproduk');







		$jabatan->m_templateproduk 				= $this->input->post('templateproduk');







		$jabatan->m_importproduk 				= $this->input->post('importproduk');







		$jabatan->m_exportrakproduk 			= $this->input->post('exportrakproduk');







		$jabatan->m_cekcustomerproduk 			= $this->input->post('cekcustomerproduk');







		$jabatan->m_clearcheckproduk 			= $this->input->post('clearcheckproduk');







		$jabatan->m_produkrusak 				= $this->input->post('produkrusak');







		$jabatan->m_tambahprodukrusak 			= $this->input->post('tambahprodukrusak');







		$jabatan->m_editprodukrusak 			= $this->input->post('editprodukrusak');







		$jabatan->m_hapusprodukrusak 			= $this->input->post('hapusprodukrusak');







		$jabatan->m_checkprodukrusak 			= $this->input->post('checkprodukrusak');







		$jabatan->m_customer 					= $this->input->post('customer');







		$jabatan->m_customervip 				= '0';







		$jabatan->m_buttoncustomervip 			= $this->input->post('buttoncustomervip');







		$jabatan->m_customerbaru 				= $this->input->post('customerbaru');







		$jabatan->m_customerqontak 				= $this->input->post('customerqontak');







		$jabatan->m_editcustomerqontak 			= $this->input->post('editcustomerqontak');







		$jabatan->m_hapuscustomerqontak 		= $this->input->post('hapuscustomerqontak');







		$jabatan->m_customerwebsite 			= $this->input->post('customerwebsite');







		$jabatan->m_editcustomerwebsite 		= $this->input->post('editcustomerwebsite');







		$jabatan->m_hapuscustomerwebsite 		= $this->input->post('hapuscustomerwebsite');







		$jabatan->m_customergoogleads 			= $this->input->post('customergoogleads');







		$jabatan->m_editcustomergoogleads 		= $this->input->post('editcustomergoogleads');







		$jabatan->m_hapuscustomergoogleads 		= $this->input->post('hapuscustomergoogleads');







		$jabatan->m_tambahcustomerbaru 			= $this->input->post('tambahcustomerbaru');







		$jabatan->m_editcustomerbaru 			= $this->input->post('editcustomerbaru');







		$jabatan->m_hapuscustomerbaru 			= $this->input->post('hapuscustomerbaru');







		$jabatan->m_customerleads 				= $this->input->post('customerleads');







		$jabatan->m_tambahcustomerleads 		= $this->input->post('tambahcustomerleads');







		$jabatan->m_editcustomerleads 			= $this->input->post('editcustomerleads');







		$jabatan->m_hapuscustomerleads 			= $this->input->post('hapuscustomerleads');







		$jabatan->m_movecustomerleads 			= '0';







		$jabatan->m_mergecustomerleads 			= $this->input->post('mergecustomerleads');







		$jabatan->m_masterkaskeluar 			= $this->input->post('masterkaskeluar');







		$jabatan->m_laporankaskeluar 			= $this->input->post('laporankaskeluar');







		$jabatan->m_laporanexportkaskeluar 		= $this->input->post('laporanexportkaskeluar');







		$jabatan->m_laporanexportxlskaskeluar 	= $this->input->post('laporanexportxlskaskeluar');







		$jabatan->m_kaskeluar 					= $this->input->post('kaskeluar');







		$jabatan->m_tambahkaskeluar 			= $this->input->post('tambahkaskeluar');







		$jabatan->m_editkaskeluar 				= $this->input->post('editkaskeluar');







		$jabatan->m_hapuskaskeluar 				= $this->input->post('hapuskaskeluar');







		$jabatan->m_invoicekaskeluar 			= $this->input->post('invoicekaskeluar');







		$jabatan->m_coakaskeluar 				= $this->input->post('coakaskeluar');







		$jabatan->m_exportpdfkaskeluar 			= $this->input->post('exportpdfkaskeluar');







		$jabatan->m_exportxlskaskeluar 			= $this->input->post('exportxlskaskeluar');







		$jabatan->m_master 						= $this->input->post('master');







		$jabatan->m_discount 					= $this->input->post('discount');







		$jabatan->m_loker 						= $this->input->post('loker');







		$jabatan->m_browseloker 				= $this->input->post('browseloker');







		$jabatan->m_checkstarloker 				= $this->input->post('checkstarloker');







		$jabatan->m_removestarloker 			= $this->input->post('removestarloker');







		$jabatan->m_viewpdfloker 				= $this->input->post('viewpdfloker');







		$jabatan->m_detailloker 				= $this->input->post('detailloker');







		$jabatan->m_hapusloker 					= $this->input->post('hapusloker');







		$jabatan->m_penjualan 					= $this->input->post('penjualan');







		$jabatan->m_tambahpenjualan 			= $this->input->post('tambahpenjualan');







		$jabatan->m_editpenjualan 				= $this->input->post('editpenjualan');







		$jabatan->m_menupenjualan 				= $this->input->post('menupenjualan');







		$jabatan->m_suratjalanpenjualan 		= $this->input->post('suratjalanpenjualan');







		$jabatan->m_qrcodepenjualan 			= '0';







		$jabatan->m_printpenjualan 				= '0';







		$jabatan->m_viewpenjualan 				= $this->input->post('viewpenjualan');







		$jabatan->m_hapuspenjualan 				= $this->input->post('hapuspenjualan');







		$jabatan->m_kirimpenjualan 				= '0';







		$jabatan->m_checklistpenjualan 			= $this->input->post('checklistpenjualan');







		$jabatan->m_printsuratpenjualan 		= $this->input->post('printsuratpenjualan');







		$jabatan->m_printinvoicepenjualan 		= $this->input->post('printinvoicepenjualan');







		$jabatan->m_voidpenjualan 				= $this->input->post('voidpenjualan');







		$jabatan->m_hapusinvoicepenjualan 		= $this->input->post('hapusinvoicepenjualan');







		$jabatan->m_customerlabel 				= $this->input->post('customerlabel');







		$jabatan->m_customerexport 				= $this->input->post('customerexport');







		$jabatan->m_customeraudience 			= $this->input->post('customeraudience');







		$jabatan->m_exportproduk 				= $this->input->post('exportproduk');







		$jabatan->m_pricelistproduk 			= $this->input->post('pricelistproduk');







		$jabatan->m_waqiscus 					= $this->input->post('waqiscus');







		$jabatan->m_retrywaqiscus 				= $this->input->post('retrywaqiscus');







		$jabatan->m_wapancake 					= $this->input->post('wapancake');







		$jabatan->m_ekspedisi 					= $this->input->post('ekspedisi');







		$jabatan->m_tambahekspedisi 			= $this->input->post('tambahekspedisi');







		$jabatan->m_editekspedisi 				= $this->input->post('editekspedisi');







		$jabatan->m_hapusekspedisi 				= $this->input->post('hapusekspedisi');







		$jabatan->m_coa 						= $this->input->post('coa');







		$jabatan->m_tambahcoa 					= $this->input->post('tambahcoa');







		$jabatan->m_editcoa 					= $this->input->post('editcoa');







		$jabatan->m_hapuscoa 					= $this->input->post('hapuscoa');







		$jabatan->m_exportcoa 					= $this->input->post('exportcoa');







		$jabatan->m_klasifikasi 				= $this->input->post('klasifikasi');







		$jabatan->m_tambahklasifikasi 			= $this->input->post('tambahklasifikasi');







		$jabatan->m_editklasifikasi 			= $this->input->post('editklasifikasi');







		$jabatan->m_hapusklasifikasi 			= $this->input->post('hapusklasifikasi');







		$jabatan->m_commercialinvoice 			= $this->input->post('commercialinvoice');







		$jabatan->m_tambahcommercialinvoice 	= $this->input->post('tambahcommercialinvoice');







		$jabatan->m_editcommercialinvoice 		= $this->input->post('editcommercialinvoice');







		$jabatan->m_prosescommercialinvoice 	= '0';







		$jabatan->m_detailcommercialinvoice 	= $this->input->post('detailcommercialinvoice');







		$jabatan->m_hapuscommercialinvoice 		= $this->input->post('hapuscommercialinvoice');







		$jabatan->m_fclcontainer 				= $this->input->post('fclcontainer');







		$jabatan->m_tambahfclcontainer 			= $this->input->post('tambahfclcontainer');







		$jabatan->m_editfclcontainer 			= $this->input->post('editfclcontainer');







		$jabatan->m_tambahfclcontainer 			= $this->input->post('tambahfclcontainer');







		$jabatan->m_detailfclcontainer 			= $this->input->post('detailfclcontainer');







		$jabatan->m_hapusfclcontainer 			= $this->input->post('hapusfclcontainer');







		$jabatan->m_dailyactivities 			= $this->input->post('dailyactivities');







		$jabatan->m_tambahdailyactivities 		= $this->input->post('tambahdailyactivities');







		$jabatan->m_gudang 						= $this->input->post('gudang');







		$jabatan->m_tambahgudang 				= $this->input->post('tambahgudang');







		$jabatan->m_editgudang 					= $this->input->post('editgudang');







		$jabatan->m_hapusgudang 				= $this->input->post('hapusgudang');







		$jabatan->m_rak 						= $this->input->post('rak');







		$jabatan->m_tambahrak 					= $this->input->post('tambahrak');







		$jabatan->m_editrak 					= $this->input->post('editrak');







		$jabatan->m_hapusrak 					= $this->input->post('hapusrak');







		$jabatan->m_menusupplier 				= $this->input->post('menusupplier');







		$jabatan->m_supplier 					= $this->input->post('supplier');







		$jabatan->m_tambahsupplier 				= $this->input->post('tambahsupplier');







		$jabatan->m_editsupplier 				= $this->input->post('editsupplier');







		$jabatan->m_hapussupplier 				= $this->input->post('hapussupplier');







		$jabatan->m_supplierbank 				= $this->input->post('supplierbank');







		$jabatan->m_tambahsupplierbank 			= $this->input->post('tambahsupplierbank');







		$jabatan->m_editsupplierbank 			= $this->input->post('editsupplierbank');







		$jabatan->m_hapussupplierbank 			= $this->input->post('hapussupplierbank');







		$jabatan->m_orderpenjualan 				= $this->input->post('orderpenjualan');







		$jabatan->m_printsuratjalanorderpenjualan 	= $this->input->post('printsuratjalanorderpenjualan');







		$jabatan->m_printinvoiceorderpenjualan 		= $this->input->post('printinvoiceorderpenjualan');







		$jabatan->m_printinvoicepdforderpenjualan 	= $this->input->post('printinvoicepdforderpenjualan');







		$jabatan->m_detailorderpenjualan 			= $this->input->post('detailorderpenjualan');







		$jabatan->m_hapusorderpenjualan 			= $this->input->post('hapusorderpenjualan');







		$jabatan->m_editorderpenjualan 				= $this->input->post('editorderpenjualan');







		$jabatan->m_tambahorderpenjualan 			= $this->input->post('tambahorderpenjualan');







		$jabatan->m_pembelian 					= $this->input->post('pembelian');







		$jabatan->m_restok 						= $this->input->post('restok');







		$jabatan->m_tambahrestok 				= $this->input->post('tambahrestok');







		$jabatan->m_editrestok 					= $this->input->post('editrestok');







		$jabatan->m_hapusrestok 				= $this->input->post('hapusrestok');







		$jabatan->m_filterrestok 				= '0';







		$jabatan->m_orderpembelian 				= $this->input->post('orderpembelian');







		$jabatan->m_filterorderpembelian 		= '0';







		$jabatan->m_prosesorderpembelian 		= '0';







		$jabatan->m_editorderpembelian 			= $this->input->post('editorderpembelian');







		$jabatan->m_rejectorderpembelian 		= $this->input->post('rejectorderpembelian');







		$jabatan->m_undoorderpembelian 			= $this->input->post('undoorderpembelian');







		$jabatan->m_copylinkorderpembelian 		= '0';







		$jabatan->m_hapusorderpembelian 		= $this->input->post('hapusorderpembelian');







		$jabatan->m_stok 						= $this->input->post('stok');







		$jabatan->m_lihatstok 					= $this->input->post('lihatstok');







		$jabatan->m_opnamestok 					= $this->input->post('opnamestok');







		$jabatan->m_quotes 						= $this->input->post('quotes');







		$jabatan->m_tambahquotes 				= $this->input->post('tambahquotes');







		$jabatan->m_editquotes 					= $this->input->post('editquotes');







		$jabatan->m_hapusquotes 				= $this->input->post('hapusquotes');







		$jabatan->m_pengiriman 					= $this->input->post('pengiriman');







		$jabatan->m_penjualanpengiriman 		= $this->input->post('penjualanpengiriman');







		$jabatan->m_editpenjualanpengiriman 		= $this->input->post('editpenjualanpengiriman');







		$jabatan->m_detailpenjualanpengiriman 		= $this->input->post('detailpenjualanpengiriman');







		$jabatan->m_printpenjualanpengiriman 		= $this->input->post('printpenjualanpengiriman');







		$jabatan->m_kirimsuratpenjualanpengiriman 	= $this->input->post('kirimsuratpenjualanpengiriman');







		$jabatan->m_pindahgudangpengiriman 		= $this->input->post('pindahgudangpengiriman');







		$jabatan->m_tambahpindahgudang 			= $this->input->post('tambahpindahgudang');







		$jabatan->m_editpindahgudang 			= $this->input->post('editpindahgudang');







		$jabatan->m_suratjalanpindahgudang 		= $this->input->post('suratjalanpindahgudang');







		$jabatan->m_terimapindahgudang 			= '0';







		$jabatan->m_hapussuratpindahgudang 		= $this->input->post('hapussuratpindahgudang');







		$jabatan->m_detailpindahgudang 			= $this->input->post('detailpindahgudang');







		$jabatan->m_hapuspindahgudang 			= $this->input->post('hapuspindahgudang');







		$jabatan->m_printpindahgudang 			= $this->input->post('printpindahgudang');







		$jabatan->m_broadcast 					= '0';







		$jabatan->m_tambahbroadcast 			= '0';







		$jabatan->m_dokumen 					= '0';







		$jabatan->m_tambahdokumen 				= '0';







		$jabatan->m_editdokumen 				= '0';







		$jabatan->m_hapusdokumen 				= '0';







		$jabatan->m_detaildokumen 				= '0';







		$jabatan->m_survey 						= '0';







		$jabatan->m_menupenjualanoption 			= $this->input->post('menupenjualanoption');







		$jabatan->m_penjualanoptioncategory 		= $this->input->post('penjualanoptioncategory');







		$jabatan->m_tambahpenjualanoptioncategory 	= $this->input->post('tambahpenjualanoptioncategory');







		$jabatan->m_editpenjualanoptioncategory 	= $this->input->post('editpenjualanoptioncategory');







		$jabatan->m_hapuspenjualanoptioncategory 	= $this->input->post('hapuspenjualanoptioncategory');







		$jabatan->m_penjualanoption 			= $this->input->post('penjualanoption');







		$jabatan->m_tambahpenjualanoption 		= $this->input->post('tambahpenjualanoption');







		$jabatan->m_editpenjualanoption 		= $this->input->post('editpenjualanoption');







		$jabatan->m_hapuspenjualanoption 		= $this->input->post('hapuspenjualanoption');







		$jabatan->m_cabang 						= $this->input->post('cabang');







		$jabatan->m_tambahcabang 				= $this->input->post('tambahcabang');







		$jabatan->m_editcabang 					= $this->input->post('editcabang');







		$jabatan->m_hapuscabang 				= $this->input->post('hapuscabang');







		$jabatan->m_kategoriproduct 			= $this->input->post('kategoriproduct');







		$jabatan->m_tambahkategoriproduct 		= $this->input->post('tambahkategoriproduct');







		$jabatan->m_editkategoriproduct 		= $this->input->post('editkategoriproduct');







		$jabatan->m_hapuskategoriproduct 		= $this->input->post('hapuskategoriproduct');







		$jabatan->m_landingpage 				= $this->input->post('landingpage');







		$jabatan->m_tambahlandingpage 			= $this->input->post('tambahlandingpage');







		$jabatan->m_editlandingpage 			= $this->input->post('editlandingpage');







		$jabatan->m_hapuslandingpage 			= $this->input->post('hapuslandingpage');







		$jabatan->m_accountbank 				= $this->input->post('accountbank');







		$jabatan->m_tambahaccountbank 			= $this->input->post('tambahaccountbank');







		$jabatan->m_editaccountbank 			= $this->input->post('editaccountbank');







		$jabatan->m_hapusaccountbank 			= $this->input->post('hapusaccountbank');







		$jabatan->m_termin 						= $this->input->post('termin');







		$jabatan->m_tambahtermin 				= $this->input->post('tambahtermin');







		$jabatan->m_edittermin 					= $this->input->post('edittermin');







		$jabatan->m_hapustermin 				= $this->input->post('hapustermin');







		$jabatan->m_matauang 					= $this->input->post('matauang');







		$jabatan->m_tambahmatauang 				= $this->input->post('tambahmatauang');







		$jabatan->m_editmatauang 				= $this->input->post('editmatauang');







		$jabatan->m_hapusmatauang 				= $this->input->post('hapusmatauang');







		$jabatan->m_pembelianlcl 				= $this->input->post('pembelianlcl');







		$jabatan->m_tambahpembelianlcl 			= $this->input->post('tambahpembelianlcl');







		$jabatan->m_editpembelianlcl 			= $this->input->post('editpembelianlcl');







		$jabatan->m_hapuspembelianlcl 			= $this->input->post('hapuspembelianlcl');







		$jabatan->m_detailpembelianlcl 			= $this->input->post('detailpembelianlcl');







		$jabatan->m_laporananalisa 				= $this->input->post('laporananalisa');







		$jabatan->m_laporananalisaservice 		= $this->input->post('laporananalisaservice');







		$jabatan->m_laporananalisapenjualan 	= $this->input->post('laporananalisapenjualan');







		$jabatan->m_laporananalisapembelian 	= $this->input->post('laporananalisapembelian');







		$jabatan->m_laporananalisakaskeluar 	= $this->input->post('laporananalisakaskeluar');







		$jabatan->m_laporananalisacustomer 		= $this->input->post('laporananalisacustomer');







		$jabatan->m_laporananalisaregresi 		= $this->input->post('laporananalisaregresi');







		$jabatan->m_laporananalisaorderpenjualan 	= $this->input->post('laporananalisaorderpenjualan');







		$jabatan->m_laporananalisaanalytics 	= $this->input->post('laporananalisaanalytics');







		$jabatan->m_laporanmutasistok 			= $this->input->post('laporanmutasistok');







		$jabatan->m_itemgroup 					= $this->input->post('itemgroup');







		$jabatan->m_tambahitemgroup 			= $this->input->post('tambahitemgroup');







		$jabatan->m_edititemgroup 				= $this->input->post('edititemgroup');







		$jabatan->m_hapusitemgroup 				= $this->input->post('hapusitemgroup');







		$jabatan->m_penerimaan 					= $this->input->post('penerimaan');







		$jabatan->m_penerimaanpembelian 		= $this->input->post('penerimaanpembelian');







		$jabatan->m_tambahpenerimaanpembelian 	= $this->input->post('tambahpenerimaanpembelian');







		$jabatan->m_editpenerimaanpembelian 	= $this->input->post('editpenerimaanpembelian');







		$jabatan->m_hapuspenerimaanpembelian 	= $this->input->post('hapuspenerimaanpembelian');







		$jabatan->m_detailpenerimaanpembelian 	= $this->input->post('detailpenerimaanpembelian');







		$jabatan->m_invoicepenerimaanpembelian 	= $this->input->post('invoicepenerimaanpembelian');







		$jabatan->m_kodedetailpenerimaanpembelian 	= $this->input->post('kodedetailpenerimaanpembelian');







		$jabatan->m_penerimaanpindahgudang 		= $this->input->post('penerimaanpindahgudang');







		$jabatan->m_tambahpenerimaanpindahgudang = $this->input->post('tambahpenerimaanpindahgudang');







		$jabatan->m_editpenerimaanpindahgudang 	= $this->input->post('editpenerimaanpindahgudang');







		$jabatan->m_hapuspenerimaanpindahgudang = $this->input->post('hapuspenerimaanpindahgudang');







		$jabatan->m_penerimaanbaranglain 		= $this->input->post('penerimaanbaranglain');







		$jabatan->m_tambahpenerimaanbaranglain 	= $this->input->post('tambahpenerimaanbaranglain');







		$jabatan->m_editpenerimaanbaranglain 	= $this->input->post('editpenerimaanbaranglain');







		$jabatan->m_hapuspenerimaanbaranglain 	= $this->input->post('hapuspenerimaanbaranglain');







		$jabatan->m_printpenerimaanbaranglain 	= $this->input->post('printpenerimaanbaranglain');







		$jabatan->m_penerimaandokumen 			= $this->input->post('penerimaandokumen');







		$jabatan->m_tambahpenerimaandokumen 	= $this->input->post('tambahpenerimaandokumen');







		$jabatan->m_editpenerimaandokumen 		= $this->input->post('editpenerimaandokumen');







		$jabatan->m_hapuspenerimaandokumen 		= $this->input->post('hapuspenerimaandokumen');







		$jabatan->m_printpenerimaandokumen 		= $this->input->post('printpenerimaandokumen');







		$jabatan->m_subkategoriproduct 			= $this->input->post('subkategoriproduct');







		$jabatan->m_tambahsubkategoriproduct 	= $this->input->post('tambahsubkategoriproduct');







		$jabatan->m_editsubkategoriproduct 		= $this->input->post('editsubkategoriproduct');







		$jabatan->m_hapussubkategoriproduct 	= $this->input->post('hapussubkategoriproduct');







		$jabatan->m_produkmoq 					= $this->input->post('produkmoq');







		$jabatan->m_tambahprodukmoq 			= $this->input->post('tambahprodukmoq');







		$jabatan->m_editprodukmoq 				= $this->input->post('editprodukmoq');







		$jabatan->m_hapusprodukmoq 				= $this->input->post('hapusprodukmoq');







		$jabatan->m_voucher 					= $this->input->post('voucher');







		$jabatan->m_datavoucher 				= $this->input->post('datavoucher');







		$jabatan->m_tambahvoucher 				= $this->input->post('tambahvoucher');







		$jabatan->m_editvoucher 				= $this->input->post('editvoucher');







		$jabatan->m_hapusvoucher 				= $this->input->post('hapusvoucher');







		$jabatan->m_vouchercustomer 			= $this->input->post('vouchercustomer');







		$jabatan->m_hapusvouchercustomer 		= $this->input->post('hapusvouchercustomer');







		$jabatan->m_sync 						= $this->input->post('sync');







		$jabatan->m_penjualansync 				= $this->input->post('penjualansync');







		$jabatan->m_orderpenjualansync 			= $this->input->post('orderpenjualansync');







		$jabatan->m_servicesync 				= $this->input->post('servicesync');







		$jabatan->m_pembayaran 					= $this->input->post('pembayaran');







		$jabatan->m_penjualanpembayaran 		= $this->input->post('penjualanpembayaran');







		$jabatan->m_editpenjualanpembayaran 	= $this->input->post('editpenjualanpembayaran');







		$jabatan->m_detailpenjualanpembayaran 	= $this->input->post('detailpenjualanpembayaran');







		$jabatan->m_uploadpenjualanpembayaran 	= $this->input->post('uploadpenjualanpembayaran');







		$jabatan->m_hapusuploadpenjualanpembayaran 	= $this->input->post('hapusuploadpenjualanpembayaran');







		$jabatan->m_listsparepart 				= $this->input->post('listsparepart');







		$jabatan->m_tambahlistsparepart 		= $this->input->post('tambahlistsparepart');







		$jabatan->m_cancellistsparepart 		= $this->input->post('cancellistsparepart');







		$jabatan->m_hapuslistsparepart 			= $this->input->post('hapuslistsparepart');







		$jabatan->m_detaillistsparepart 		= $this->input->post('detaillistsparepart');







		$jabatan->m_menureview                  = $this->input->post('menureview');







		$jabatan->m_reviewservice               = $this->input->post('reviewservice');







		$jabatan->m_reviewservicesend           = $this->input->post('reviewservicesend');







		$jabatan->m_reviewservicevalue          = $this->input->post('reviewservicevalue');







		$jabatan->m_reviewpenjualan             = $this->input->post('reviewpenjualan');







		$jabatan->m_reviewpenjualansend         = $this->input->post('reviewpenjualansend');







		$jabatan->m_reviewpenjualanvalue        = $this->input->post('reviewpenjualanvalue');







		$jabatan->m_penjualanboard 				= $this->input->post('penjualanboard');







		$jabatan->m_tambahpenjualanboard 		= $this->input->post('tambahpenjualanboard');







		$jabatan->m_editpenjualanboard 			= $this->input->post('editpenjualanboard');







		$jabatan->m_hapuspenjualanboard 		= $this->input->post('hapuspenjualanboard');







		$jabatan->m_lembur 						= '0';







		$jabatan->m_alarmlembur 				= '0';







		$jabatan->m_masterppn 					= $this->input->post('masterppn');







		$jabatan->m_masterrmbtousd 				= $this->input->post('masterrmbtousd');







		$jabatan->m_flyer 						= $this->input->post('flyer');







		$jabatan->m_tambahflyer 				= $this->input->post('tambahflyer');







		$jabatan->m_editflyer 					= $this->input->post('editflyer');







		$jabatan->m_hapusflyer 					= $this->input->post('hapusflyer');







		$jabatan->m_servicereimbursement 		= $this->input->post('servicereimbursement');







		$jabatan->m_tambahservicereimbursement 	= $this->input->post('tambahservicereimbursement');







		$jabatan->m_editservicereimbursement 	= $this->input->post('editservicereimbursement');







		$jabatan->m_hapusservicereimbursement 	= $this->input->post('hapusservicereimbursement');







		$jabatan->m_printservicereimbursement 	= $this->input->post('printservicereimbursement');







		$jabatan->m_peminjamanorderpenjualan 	= $this->input->post('peminjamanorderpenjualan');







		$jabatan->m_hapuspeminjamanorderpenjualan 			= $this->input->post('hapuspeminjamanorderpenjualan');







		$jabatan->m_pengembalianpeminjamanorderpenjualan 	= $this->input->post('pengembalianpeminjamanorderpenjualan');







		$jabatan->m_menupancake 				= $this->input->post('menupancake');







		$jabatan->m_pancake 					= $this->input->post('pancake');







		$jabatan->m_retrypancake 				= $this->input->post('retrypancake');







		$jabatan->m_pancaketemplate 			= $this->input->post('pancaketemplate');







		$jabatan->m_tambahpancaketemplate 		= $this->input->post('tambahpancaketemplate');







		$jabatan->m_editpancaketemplate 		= $this->input->post('editpancaketemplate');







		$jabatan->m_hapuspancaketemplate 		= $this->input->post('hapuspancaketemplate');







		$jabatan->m_statusorder 				= $this->input->post('statusorder');







		$jabatan->m_tambahstatusorder 			= $this->input->post('tambahstatusorder');







		$jabatan->m_editstatusorder 			= $this->input->post('editstatusorder');







		$jabatan->m_hapusstatusorder 			= $this->input->post('hapusstatusorder');







		if ($jabatan->save()) {







			echo json_encode("Jabatan berhasil di ubah");







			return;

		} else {







			echo json_encode("Jabatan gagal di ubah");







			return;

		}

	}







	//API Posisi Karyawan



	public function posisi_user_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter                 = $this->input->get('name');







		if (!$nameFilter) {







			$valuename              = '';

		} else {







			$valuename              = $nameFilter;

		}







		$position                  = PositionKaryawanModel::where('name', 'like', '%' . $valuename . '%')->orderBy('name', 'asc')->get();











		$total                      = count($position);







		$data['msg'] = 'success';



		$data['auth'] = true;



		$data['position']           = PositionKaryawanModel::take(20)->where('name', 'like', '%' . $valuename . '%')->orderBy('name', 'asc')->get();











		$data['nameFilter']         = $valuename;







		echo json_encode($data);

	}







	//API Form Edit Posisi Karyawan



	public function posisi_user_formedit_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$position 						= PositionKaryawanModel::find($id);



		$data['position'] = $position;



		echo json_encode($data);

	}







	//API Tambah Posisi User



	public function posisi_user_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules 		= [







			'required' 	=> [







				['posisi'], ['jobdesk'], ['kpi'], ['sop'], ['deskripsi']







			]







		];



		$validate 	= Validation::check($rules, 'post');



		if (!$validate->auth) {



			$response = array(



				'error' => 'Gagal lolos validasi',



				'msg' => $validate



			);



			echo json_encode($response);







			return;

		}



		$position 						= new PositionKaryawanModel;







		$position->name 				= $this->input->post('posisi');







		$position->job 					= $this->input->post('jobdesk');







		$position->kpi 					= $this->input->post('kpi');







		$position->sop 					= $this->input->post('sop');







		$position->deskripsi 			= $this->input->post('deskripsi');















		if ($position->save()) {



			$response = array(



				'msg' => 'success',



				'data' => $position,



			);



			echo json_encode($response);







			return;

		} else {







			echo json_encode('Posisi karyawan gagal di tambah');







			return;

		}

	}







	//API PUT Posisi User



	public function posisi_user_edit_put()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$input_data = json_decode(trim(file_get_contents('php://input')), true);







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			$id = isset($input_data['id']) ? $input_data['id'] : null;











			$position 						= PositionKaryawanModel::find($id);















			if (!$position) {







				echo goResult(false, 'Maaf, posisi yang anda pilih tidak ada');







				return;

			}















			$position->name 				= isset($input_data['posisi']) ? $input_data['posisi'] : null;







			$position->job 					= isset($input_data['jobdesk']) ? $input_data['jobdesk'] : null;







			$position->kpi 					= isset($input_data['kpi']) ? $input_data['kpi'] : null;







			$position->sop 					= isset($input_data['sop']) ? $input_data['sop'] : null;







			$position->deskripsi 			= isset($input_data['deskripsi']) ? $input_data['deskripsi'] : null;















			if ($position->save()) {



				$data = array(



					'msg' => 'Posisi karyawan berhasil di edit',



					'data' => $position



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('Posisi karyawan gagal di edit');







				return;

			}

		} else {







			$id = $this->input->post('id');



			$rules 		= [







				'required' 	=> [







					['posisi'], ['jobdesk'], ['kpi'], ['sop'], ['deskripsi']







				]







			];







			$validate 	= Validation::check($rules, 'post');















			if (!$validate->auth) {



				$response = array(



					'error' => 'Gagal lolos validasi',



					'msg' => $validate



				);



				echo json_encode($response);







				return;

			}















			$position 						= PositionKaryawanModel::find($id);







			if (!$position) {







				echo json_encode('Maaf, posisi yang anda pilih tidak ada');







				return;

			}







			$position->name 				= $this->input->post('posisi');







			$position->job 					= $this->input->post('jobdesk');







			$position->kpi 					= $this->input->post('kpi');







			$position->sop 					= $this->input->post('sop');







			$position->deskripsi 			= $this->input->post('deskripsi');















			if ($position->save()) {



				$data = array(



					'msg' => 'Posisi karyawan berhasil di edit',



					'data' => $position



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('Posisi karyawan gagal di edit');







				return;

			}

		}

	}







	//API Employee



	public function employee_user_get()



	{











		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter                 = $this->input->get('name');



		$positionFilter             = $this->input->get('position');







		if (!$nameFilter) {



			$valuename                 = '';

		} else {



			$valuename                 = $nameFilter;

		}







		if (!$positionFilter) {



			$valueposition             = 'all';

		} else {



			$valueposition             = $positionFilter;

		}







		if ($valueposition != 'all') {







			if ($teknisi->status != 'manager' && $teknisi->status != 'super_admin') {



				$datateknisi         = KaryawanModel::where('status', 'teknisi')->where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->where('id_position', $valueposition)->orderBy('name', 'asc')->get();

			} else {



				$datateknisi         = KaryawanModel::where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->where('id_position', $valueposition)->orderBy('name', 'asc')->get();

			}

		} else {







			if ($teknisi->status != 'manager' && $teknisi->status != 'super_admin') {



				$datateknisi         = KaryawanModel::where('status', 'teknisi')->where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->orderBy('name', 'asc')->get();

			} else {



				$datateknisi         = KaryawanModel::where('status_regis', 1)->where('name', 'LIKE', '%' . $valuename . '%')->orderBy('name', 'asc')->get();

			}

		}







		$idTeknisi                     = array();



		foreach ($datateknisi as $key => $value) {



			$idTeknisi[]             = $value->id;

		}







		$total                        = count($idTeknisi);



		$data['datateknisi']         = KaryawanModel::whereIn('id', $idTeknisi)->take(20)->get();



		$data['nameFilter']         = $valuename;



		$data['positionFilter']     = $valueposition;







		echo json_encode($data);

	}







	//API Data Item



	public function data_item_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$typefilter 				= $this->input->get('typeFilter');











		$categoryfilter 			= $this->input->get('categoryFilter');







		$nameFilter 				= $this->input->get('name');















		if (!$typefilter) {







			$valuetype 				= 'spesifikasi';

		} else {







			$valuetype 				= $typefilter;

		}















		if (!$categoryfilter) {







			$valuecategory 			= 'all';

		} else {







			$valuecategory 			= $categoryfilter;

		}















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$product 					= BarangModel::where('name', 'like', '%' . $valuename . '%')->orWhere('new_kode', 'like', '%' . $valuename . '%')->orderBy('new_kode', 'asc')->get();















		$idProduct 					= array();







		foreach ($product as $key => $value) {







			$idProduct[] 			= $value->id;

		}















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {



			$page_next = $this->input->get('page');



			$page 					= $page_next;

		}















		$paginate					= new Myweb_pagination;















		if ($valuecategory != 'all') {















			$countproduct 			= BarangModel::whereIn('id', $idProduct)->where('id_category', $valuecategory)->where('status_deleted', '0')->orderBy('status_check', 'desc')->orderBy('new_kode', 'asc')->get();







			$allproduct 			= BarangModel::take(20)->skip($page * 20)->whereIn('id', $idProduct)->where('id_category', $valuecategory)->where('status_deleted', '0')->orderBy('status_check', 'desc')->orderBy('new_kode', 'asc')->get();

		} else {















			$countproduct 			= BarangModel::whereIn('id', $idProduct)->where('status_deleted', '0')->orderBy('status_check', 'desc')->orderBy('new_kode', 'asc')->get();







			$allproduct 			= BarangModel::take(20)->skip($page * 20)->whereIn('id', $idProduct)->where('status_deleted', '0')->orderBy('status_check', 'desc')->orderBy('new_kode', 'asc')->get();

		}















		$total						= count($countproduct);







		$data['allproduct'] 		= $allproduct;







		$data['numberpage'] 		= $page * 20;



















		$data['typefilter'] 		= $valuetype;







		$data['categoryfilter'] 	= $valuecategory;







		$data['nameFilter'] 		= $valuename;







		$data['hscode'] 			= BarangHscodeModel::asc()->get();



		echo json_encode($data);

	}







	//API Edit Form Data Item



	public function data_item_formedit_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}















		$id = $this->input->get('id');



		$product = BarangModel::find($id);







		$data['product']                = BarangModel::find($id);



		$stok = BarangstokModel::where('id_barang', $id)->get();











		$price                          = BarangPriceModel::where('id_barang', $id)->first();







		if (!$price) {







			$data['status_price']       = 'false';

		} else {







			$data['status_price']       = 'true';







			$data['price']              = BarangPriceModel::where('id_barang', $id)->first();

		}















		if ($data['product']->status_customcode == '0') {







			if ($data['product']->id_category == 1) {







				$kode                       = str_replace('MES', '', $data['product']->new_kodesku);







				$oldkode                    = $data['product']->kode;

			} else {







				$kode                       = substr($data['product']->new_kodesku, 1);







				$oldkode                    = $data['product']->kode;

			}

		} else {







			$kode                       = $data['product']->new_kode;







			$oldkode                    = $data['product']->new_kode;

		}















		$data['kode']                   = $kode;







		$data['oldkode']                = $oldkode;















		$data['barangtype']             = BarangTypeModel::asc()->get();















		$data['pricelist']              = PriceDiscountModel::find(1);







		$data['cust_baru']              = PriceDiscountModel::find(2);







		$data['cust_loyal']             = PriceDiscountModel::find(3);







		$data['cust_vip']               = PriceDiscountModel::find(4);







		$data['reseller']               = PriceDiscountModel::find(5);







		$data['reseller_vip']           = PriceDiscountModel::find(6);















		$data['pricelistspr']           = PriceDiscountSparepartModel::find(1);







		$data['cust_baruspr']           = PriceDiscountSparepartModel::find(2);







		$data['cust_loyalspr']          = PriceDiscountSparepartModel::find(3);







		$data['cust_vipspr']            = PriceDiscountSparepartModel::find(4);







		$data['resellerspr']            = PriceDiscountSparepartModel::find(5);







		$data['reseller_vipspr']        = PriceDiscountSparepartModel::find(6);















		$data['pricelistbb']            = PriceDiscountBahanBakuModel::find(1);







		$data['cust_barubb']            = PriceDiscountBahanBakuModel::find(2);







		$data['cust_loyalbb']           = PriceDiscountBahanBakuModel::find(3);







		$data['cust_vipbb']             = PriceDiscountBahanBakuModel::find(4);







		$data['resellerbb']             = PriceDiscountBahanBakuModel::find(5);







		$data['reseller_vipbb']         = PriceDiscountBahanBakuModel::find(6);















		$discount_custom                    = BarangDiscountCustomModel::where('id_barang', $id)->first();







		if ($discount_custom) {







			$data['discount_custom']        = BarangDiscountCustomModel::find($discount_custom->id);







			$data['discount_customstatus']  = 'true';

		} else {







			$data['discount_customstatus']  = 'false';

		}



		if (!$stok) {



			$data['stok'] = '';

		} else {



			$data['stok'] = BarangstokModel::where('id_barang', $id)->get();

		}



		echo json_encode($data);

	}







	//API Edit Data Item



	public function data_item_edit_post()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules 		= [







			'required' 	=> [







				['name'], ['type'], ['category'], ['group'], ['subcategory'], ['spesification'], ['value']







			]







		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {







			echo json_encode($validate);







			return;

		}











		$id = $this->input->post('id');



		$product 					= BarangModel::find($id);















		foreach ($product->images as $result) {







			if (!$this->input->post('available_' . $result->id)) {















				BarangImageModel::where('id', $result->id)->delete();







				remFile(__DIR__ . '/../../public_html/images/barang/' . $result->image);

			} elseif (!empty($_FILES['image_' . $result->id]['name']) && $this->isImage('image_' . $result->id) == true) {















				$filename 	= seo($this->input->post('name'));







				$upload 	= $this->upload('images/barang/', 'image_' . $result->id, $filename);















				if ($upload['auth']	== false) {







					echo goResult(false, $upload['msg']);







					return;

				}















				remFile(__DIR__ . '/../../public_html/images/barang/' . $result->image);







				BarangImageModel::where('id', $result->id)->update(['image' => $upload['msg']['file_name']]);

			}

		}























		if ($this->input->post('status_customcode') == '0') {







			$product->kode				= $this->input->post('oldcode');







			if ($this->input->post('category') == 1) {







				$product->new_kode 		= 'M' . $this->input->post('code');







				$product->new_kodesku 	= 'MES' . $this->input->post('code');

			} elseif ($this->input->post('category') == 2) {







				$product->new_kode 		= 'B' . $this->input->post('code');







				$product->new_kodesku 	= 'B' . $this->input->post('code');

			} elseif ($this->input->post('category') == 3) {







				$product->new_kode 		= 'S' . $this->input->post('code');







				$product->new_kodesku 	= 'S' . $this->input->post('code');

			} else {







				$product->new_kode 		= 'L' . $this->input->post('code');







				$product->new_kodesku 	= 'L' . $this->input->post('code');

			}

		} else {







			$product->kode			= $this->input->post('code');







			$product->new_kode 		= $this->input->post('code');







			$product->new_kodesku 	= $this->input->post('code');

		}















		$product->barcode			= '';







		$product->name				= $this->input->post('name');







		$product->name_english		= $this->input->post('name_english');







		$product->name_china		= $this->input->post('name_china');







		$product->id_type			= $this->input->post('type');







		$product->id_group			= $this->input->post('group');







		$product->id_category		= $this->input->post('category');







		$product->id_subcategory	= $this->input->post('subcategory');







		$product->merk				= $this->input->post('merk');







		$product->description		= '-';







		$product->spesification		= $this->input->post('spesification');







		$product->value				= $this->input->post('value');







		$product->kode_rak			= $this->input->post('kode_rak');







		$product->status_customcode	= $this->input->post('status_customcode');















		if ($product->save()) {















			if (isset($_FILES['image'])) {







				$filename 	= seo($this->input->post('name'));















				$upload 	= $this->upload_files(__DIR__ . '/../../public_html/images/barang', $_FILES['image'], $filename);







				if ($upload['auth']	== false) {







					$product->delete();







					foreach ($upload['msg'] as $key => $value) {







						remFile(__DIR__ . '/../../public_html/images/barang/' . $value['file_name']);

					}







					echo goResult(false, 'Opss! Gambar Product Tidak Terupload');







					return;

				}















				foreach ($upload['msg'] as $key => $value) {







					$image 					= new BarangImageModel;







					$image->id_barang 		= $product->id;







					$image->image 			= $value['file_name'];







					$image->save();

				}

			}















			$image 						= BarangImageModel::where('id_barang', $product->id)->first();















			if ($image) {







				$product->image 		= $image->image;







				$product->save();

			}















			$data['auth'] 				= true;







			$data['msg'] 				= 'Spesifikasi telah berhasil diubah';







			$data['product'] 				= $product;



			unset($data['product']['id']);



			echo toJson($data);







			return;

		} else {







			echo goResult(false, 'Spesifikasi gagal diubah');







			return;

		}







		// }











	}







	//API Edit Data Item Informasi



	public function data_item_edit_informasi_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		// $input_data = json_decode(trim(file_get_contents('php://input')), true);



		// if($_SERVER['REQUEST_METHOD'] === 'PUT'){



		// 	$id = isset($input_data['id']) ? $input_data['id'] : null;



		// 	$product 					= BarangModel::find($id);







		// 	$product->long				= isset($input_data['long']) ? $input_data['long'] : null;



		// 	$product->width				= isset($input_data['width']) ? $input_data['width'] : null;



		// 	$product->height			= isset($input_data['height']) ? $input_data['height'] : null;



		// 	$product->cbm				= isset($input_data['cbm']) ? $input_data['cbm'] : null;



		// 	$product->weight			= isset($input_data['weight']) ? $input_data['weight'] : null;



		// 	$editppn = isset($input_data['ppn']) ? $input_data['ppn'] : null;



		// 	$product->ppn 				= (null !== $editppn ? 'TRUE' : 'FALSE');







		// 	$product->status_image 		= 'Ada';







		// 	$product->status_for_mxp 	= 1;







		// 	if($product->save()){











		// 		if(isset($input_data['hscode']) ? $input_data['hscode'] : null){







		// 			$oldhscode 				= BarangHscodeModel::where('id_barang', $product->id)->delete();







		// 			for ($i=0; $i < count(isset($input_data['hscode']) ? $input_data['hscode'] : null); $i++) { 







		// 				$hscode 			= new BarangHscodeModel;







		// 				$hscode->id_barang 	= $product->id;







		// 				$hscode->code 		= isset($input_data['hscode']) ? $input_data['hscode'] : null[$i];







		// 				$hscode->save();







		// 			}







		// 		}















		// 		echo json_encode('Informasi berhasil diubah');







		// 		return;







		// 	}else{







		// 		echo json_encode('Informasi gagal diubah');







		// 		return;







		// 	}







		// }



		// else{



		$id = $this->input->post('id');



		$product 					= BarangModel::find($id);















		$product->long				= $this->input->post('long');







		$product->width				= $this->input->post('width');







		$product->height			= $this->input->post('height');







		$product->cbm				= $this->input->post('cbm');







		$product->weight			= $this->input->post('weight');







		$product->ppn 				= (null !== $this->input->post('ppn') ? 'TRUE' : 'FALSE');







		$product->status 			= 'TRUE';







		$product->status_image 		= 'Ada';







		$product->status_for_mxp 	= 1;















		if ($product->save()) {















			if ($this->input->post('hscode')) {







				$oldhscode 				= BarangHscodeModel::where('id_barang', $product->id)->delete();















				for ($i = 0; $i < count($this->input->post('hscode')); $i++) {







					$hscode 			= new BarangHscodeModel;







					$hscode->id_barang 	= $product->id;







					$hscode->code 		= $this->input->post('hscode')[$i];







					$hscode->save();

				}

			}



			$update = array(



				'msg' => 'Informasi berhasil diubah',



				'data' => $product



			);



			unset($update['data']['id']);







			echo json_encode($update);







			return;

		} else {







			echo json_encode('Informasi gagal diubah');







			return;

		}



		// }







	}







	//API Get data to Tambah Data Item



	public function data_item_getdata_tambah_get()



	{







		$data['mdiscpricelist'] 	= PriceDiscountModel::find(1);







		$data['mdisccustbaru'] 		= PriceDiscountModel::find(2);







		$data['mdisccustloyal'] 	= PriceDiscountModel::find(3);







		$data['mdisccustvip'] 		= PriceDiscountModel::find(4);







		$data['mdiscreseller'] 		= PriceDiscountModel::find(5);







		$data['mdiscresellervip'] 	= PriceDiscountModel::find(6);







		$data['lastimport'] 		= ImportModel::find(1);







		$data['gudang'] 			= GudangModel::where('status', '1')->orderBy('name', 'asc')->get();







		$data['rak'] 				= RakModel::where('status', '1')->orderBy('code', 'asc')->get();







		$data['baranggroup'] 		= BaranggroupModel::where('status', '1')->orderBy('name', 'asc')->get();







		$data['category'] 			= BarangcategoryModel::where('status', '1')->orderBy('name', 'asc')->get();







		$data['subcategory'] 		= BarangsubcategoryModel::where('status', '1')->orderBy('name', 'asc')->get();



		$data['barangtype'] 			= BarangTypeModel::asc()->get();







		$data['pricelist'] 				= PriceDiscountModel::find(1);







		$data['cust_baru'] 				= PriceDiscountModel::find(2);







		$data['cust_loyal'] 			= PriceDiscountModel::find(3);







		$data['cust_vip'] 				= PriceDiscountModel::find(4);







		$data['reseller'] 				= PriceDiscountModel::find(5);







		$data['reseller_vip'] 			= PriceDiscountModel::find(6);















		$data['pricelistspr'] 			= PriceDiscountSparepartModel::find(1);







		$data['cust_baruspr'] 			= PriceDiscountSparepartModel::find(2);







		$data['cust_loyalspr'] 			= PriceDiscountSparepartModel::find(3);







		$data['cust_vipspr'] 			= PriceDiscountSparepartModel::find(4);







		$data['resellerspr'] 			= PriceDiscountSparepartModel::find(5);







		$data['reseller_vipspr'] 		= PriceDiscountSparepartModel::find(6);















		$data['pricelistbb'] 			= PriceDiscountBahanBakuModel::find(1);







		$data['cust_barubb'] 			= PriceDiscountBahanBakuModel::find(2);







		$data['cust_loyalbb'] 			= PriceDiscountBahanBakuModel::find(3);







		$data['cust_vipbb'] 			= PriceDiscountBahanBakuModel::find(4);







		$data['resellerbb'] 			= PriceDiscountBahanBakuModel::find(5);







		$data['reseller_vipbb'] 		= PriceDiscountBahanBakuModel::find(6);















		//generate code







		$str = "";







		for ($i = 0; $i < 1; $i++) {







			$characters = array_merge(range('0', '9'));







			$max = count($characters) - 1;







			for ($j = 0; $j < 5; $j++) {







				$rand = mt_rand(0, $max);







				$str .= $characters[$rand];

			}















			$barangOld 			= BarangModel::where('kode', $str)->get();







			if (count($barangOld) > 0) {







				$str 	= "";







				$i 		= 0;

			}

		}















		$data['kode'] 			= $str;







		echo goResult(true, $data);

	}



	//API Edit Data Item Price



	public function data_item_edit_price_put()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$input_data = json_decode(file_get_contents('php://input'), true);







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			$status_price = isset($input_data['status_price']) ? $input_data['status_price'] : null;



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$barang 					= BarangModel::find($id);











			$barang->status_price 		= $status_price;







			$barang->save();







			if ($status_price == 'Custom') {







				$olddiscount_custom 			= BarangDiscountCustomModel::where('id_barang', $id)->first();



				if ($olddiscount_custom) {







					$discount_custom 				= BarangDiscountCustomModel::find($olddiscount_custom->id);







					$discount_custom->cust_new 		= isset($input_data['disc_cust_new']) ? $input_data['disc_cust_new'] : null;







					$discount_custom->cust_loyal 	= isset($input_data['disc_cust_loyal']) ? $input_data['disc_cust_loyal'] : null;







					$discount_custom->cust_vip 		= isset($input_data['disc_cust_vip']) ? $input_data['disc_cust_vip'] : null;







					$discount_custom->reseller 		= isset($input_data['disc_reseller']) ? $input_data['disc_reseller'] : null;







					$discount_custom->reseller_vip 	= isset($input_data['disc_reseller_vip']) ? $input_data['disc_reseller_vip'] : null;







					$discount_custom->save();

				} else {







					$discount_custom 				= new BarangDiscountCustomModel;







					$discount_custom->id_barang 	= $id;







					$discount_custom->price_list 	= 0;







					$discount_custom->cust_new 		= isset($input_data['disc_cust_new']) ? $input_data['disc_cust_new'] : null;







					$discount_custom->cust_loyal 	= isset($input_data['disc_cust_loyal']) ? $input_data['disc_cust_loyal'] : null;







					$discount_custom->cust_vip 		= isset($input_data['disc_cust_vip']) ? $input_data['disc_cust_vip'] : null;







					$discount_custom->reseller 		= isset($input_data['disc_reseller']) ? $input_data['disc_reseller'] : null;







					$discount_custom->reseller_vip 	= isset($input_data['disc_reseller_vip']) ? $input_data['disc_reseller_vip'] : null;















					$discount_custom->save();

				}

			} else {



				$discount_custom 				= BarangDiscountCustomModel::where('id_barang', $id)->first();







				if ($discount_custom) {







					BarangDiscountCustomModel::where('id_barang', $id)->delete();

				}

			}











			$productPrice 				= BarangPriceModel::where('id_barang', $id)->first();







			if (!$productPrice) {















				$price 					= new BarangPriceModel;







				$price->id_barang 		= $id;

			} else {







				$price 					= BarangPriceModel::where('id_barang', $id)->first();

			}











			$price->price_list			= (int) str_replace('.', '', isset($input_data['price_list']) ? $input_data['price_list'] : null);







			// $price->cust_new			= (int) str_replace('.', '',$this->input->post('cust_baru_hidden'));



			$price->cust_new			= (int) str_replace('.', '', isset($input_data['cust_baru_hidden']) ? $input_data['cust_baru_hidden'] : null);







			// $price->cust_loyal			= (int) str_replace('.', '',$this->input->post('cust_loyal_hidden'));



			$price->cust_loyal			= (int) str_replace('.', '', isset($input_data['cust_loyal_hidden']) ? $input_data['cust_loyal_hidden'] : null);







			// $price->cust_vip			= (int) str_replace('.', '',$this->input->post('cust_vip_hidden'));



			$price->cust_vip			= (int) str_replace('.', '', isset($input_data['cust_vip_hidden']) ? $input_data['cust_vip_hidden'] : null);







			// $price->reseller			= (int) str_replace('.', '',$this->input->post('reseller_hidden'));



			$price->reseller			= (int) str_replace('.', '', isset($input_data['reseller_hidden']) ? $input_data['reseller_hidden'] : null);







			// $price->reseller_vip		= (int) str_replace('.', '',$this->input->post('reseller_vip_hidden'));



			$price->reseller_vip		= (int) str_replace('.', '', isset($input_data['reseller_vip_hidden']) ? $input_data['reseller_vip_hidden'] : null);







			if ($price->save()) {



				header('Content-Type: application/json');



				echo json_encode('Price berhasil diubah');







				return;

			} else {







				echo json_encode('Price gagal diubah');







				return;

			}

		} else {







			if ($this->input->post('status_price') == 'Custom') {







				$rules 		= [







					'required' 	=> [







						['price_list'], ['cust_baru'], ['cust_loyal'], ['cust_vip'], ['reseller'], ['reseller_vip']







					]







				];















				$validate 	= Validation::check($rules, 'post');















				if (!$validate->auth) {







					echo goResult(false, $validate->msg);







					return;

				}

			} else {







				$rules 		= [







					'required' 	=> [







						['price_list']







					]







				];















				$validate 	= Validation::check($rules, 'post');















				if (!$validate->auth) {







					echo goResult(false, $validate->msg);







					return;

				}

			}







			$barang 					= BarangModel::find($id);







			$barang->status_price 		= $this->input->post('status_price');







			$barang->save();











			if ($this->input->post('status_price') == 'Custom') {







				$olddiscount_custom 			= BarangDiscountCustomModel::where('id_barang', $id)->first();







				if ($olddiscount_custom) {







					$discount_custom 				= BarangDiscountCustomModel::find($olddiscount_custom->id);







					$discount_custom->cust_new 		= $this->input->post('disc_cust_new');







					$discount_custom->cust_loyal 	= $this->input->post('disc_cust_loyal');







					$discount_custom->cust_vip 		= $this->input->post('disc_cust_vip');







					$discount_custom->reseller 		= $this->input->post('disc_reseller');







					$discount_custom->reseller_vip 	= $this->input->post('disc_reseller_vip');







					$discount_custom->save();

				} else {







					$discount_custom 				= new BarangDiscountCustomModel;







					$discount_custom->id_barang 	= $id;







					$discount_custom->price_list 	= 0;







					$discount_custom->cust_new 		= $this->input->post('disc_cust_new');







					$discount_custom->cust_loyal 	= $this->input->post('disc_cust_loyal');







					$discount_custom->cust_vip 		= $this->input->post('disc_cust_vip');







					$discount_custom->reseller 		= $this->input->post('disc_reseller');







					$discount_custom->reseller_vip 	= $this->input->post('disc_reseller_vip');







					$discount_custom->save();

				}

			} else {







				$discount_custom 				= BarangDiscountCustomModel::where('id_barang', $id)->first();







				if ($discount_custom) {







					BarangDiscountCustomModel::where('id_barang', $id)->delete();

				}

			}



			$productPrice 				= BarangPriceModel::where('id_barang', $id)->first();







			if (!$productPrice) {















				$price 					= new BarangPriceModel;







				$price->id_barang 		= $id;

			} else {















				$price 					= BarangPriceModel::where('id_barang', $id)->first();

			}











			$price->price_list			= (int) str_replace('.', '', $this->input->post('price_list'));







			$price->cust_new			= (int) str_replace('.', '', $this->input->post('cust_baru_hidden'));







			$price->cust_loyal			= (int) str_replace('.', '', $this->input->post('cust_loyal_hidden'));







			$price->cust_vip			= (int) str_replace('.', '', $this->input->post('cust_vip_hidden'));







			$price->reseller			= (int) str_replace('.', '', $this->input->post('reseller_hidden'));







			$price->reseller_vip		= (int) str_replace('.', '', $this->input->post('reseller_vip_hidden'));







			if ($price->save()) {







				echo json_encode('Price berhasil diubah');







				return;

			} else {







				echo json_encode('Price gagal diubah');







				return;

			}

		}

	}







	//API Edit Data Item Seo



	public function data_item_edit_seo_put()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$input_data = json_decode(file_get_contents('php://input'), true);







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$product 					= BarangModel::find($id);







			$product->seo_title			= isset($input_data['seo_title']) ? $input_data['seo_title'] : null;



			$product->alt_image			= isset($input_data['alt_image']) ? $input_data['alt_image'] : null;



			$product->seo_description	= isset($input_data['seo_description']) ? $input_data['seo_description'] : null;







			$product->keyword1			= isset($input_data['keyword1']) ? $input_data['keyword1'] : null;







			$product->keyword2			= isset($input_data['keyword2']) ? $input_data['keyword2'] : null;







			$product->keyword3			= isset($input_data['keyword3']) ? $input_data['keyword3'] : null;







			$product->keyword_research	= isset($input_data['keyword_research']) ? $input_data['keyword_research'] : null;







			if ($product->save()) {







				echo json_encode('Seo berhasil diubah');







				return;

			} else {







				echo json_encode('Seo gagal diubah');







				return;

			}

		} else {



			$id = $this->input->post('id');



			$product 					= BarangModel::find($id);















			$product->seo_title			= $this->input->post('seo_title');







			$product->alt_image			= $this->input->post('alt_image');







			$product->seo_description	= $this->input->post('seo_description');







			$product->keyword1			= $this->input->post('keyword1');







			$product->keyword2			= $this->input->post('keyword2');







			$product->keyword3			= $this->input->post('keyword3');







			$product->keyword_research	= $this->input->post('keyword_research');















			if ($product->save()) {







				echo json_encode('Seo berhasil diubah');







				return;

			} else {







				echo json_encode('Seo gagal diubah');







				return;

			}

		}

	}







	//API Edit Data Item Stok



	public function data_item_edit_stok_put()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$input_data = json_decode(file_get_contents('php://input'), true);



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$product 					= BarangModel::find($id);







			BarangstokModel::where('id_barang', $id)->delete();







			$gudang = isset($input_data['gudang[]']) ? (array) $input_data['gudang[]'] : [];



			$stok = isset($input_data['stok[]']) ? (array) $input_data['stok[]'] : [];







			for ($i = 0; $i < count($gudang); $i++) {







				$productStock 				= new BarangstokModel;







				$productStock->id_barang 	= $product->id;







				$productStock->id_gudang 	= $gudang[$i];







				$productStock->stok 		= $stok[$i];



				// dd($productStock->id_gudang ,$productStock->stok );







				$productStock->save();

			}



			echo json_encode('Stok berhasil diubah');







			return;

		} else {







			$id = $this->input->post('id');



			$product 					= BarangModel::find($id);







			BarangstokModel::where('id_barang', $id)->delete();















			$gudang 					= $this->input->post('gudang');







			$stok 						= $this->input->post('stok');











			for ($i = 0; $i < count($gudang); $i++) {







				$productStock 				= new BarangstokModel;







				$productStock->id_barang 	= $product->id;







				$productStock->id_gudang 	= $gudang[$i];







				$productStock->stok 		= $stok[$i];







				$productStock->save();

			}







			echo json_encode('Stok berhasil diubah');







			return;

		}

	}







	//API Tambah Data Item Spesifikasi



	public function data_item_tambah_post()



	{











		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$rules 		= [







			'required' 	=> [







				['name'], ['type'], ['category'], ['group'], ['subcategory'], ['spesification'], ['value']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo json_encode($validate);







			return;

		}







		$product 					= new BarangModel;







		foreach ($_FILES['image']['name'] as $file) {







			if (empty($file)) {







				echo goResult(false, "Opss! Gambar Tidak Ada Atau Tidak Sesuai ");







				return;

			}

		}



		$product->kode				= $this->input->post('code');







		if ($this->input->post('status_customcode') == '0') {







			if ($this->input->post('category') == 1) {







				$product->new_kode 		= 'M' . $this->input->post('code');







				$product->new_kodesku 	= 'MES' . $this->input->post('code');

			} elseif ($this->input->post('category') == 2) {







				$product->new_kode 		= 'B' . $this->input->post('code');







				$product->new_kodesku 	= 'B' . $this->input->post('code');

			} elseif ($this->input->post('category') == 3) {







				$product->new_kode 		= 'S' . $this->input->post('code');







				$product->new_kodesku 	= 'S' . $this->input->post('code');

			} else {







				$product->new_kode 		= 'L' . $this->input->post('code');







				$product->new_kodesku 	= 'L' . $this->input->post('code');

			}

		} else {







			$product->new_kode 		= $this->input->post('code');







			$product->new_kodesku 	= $this->input->post('code');

		}











		$product->barcode			= '';







		$product->name				= $this->input->post('name');







		$product->name_english		= $this->input->post('name_english');







		$product->name_china		= $this->input->post('name_china');







		$product->id_type			= $this->input->post('type');







		$product->id_group			= $this->input->post('group');







		$product->id_category		= $this->input->post('category');







		$product->id_subcategory	= $this->input->post('subcategory');







		$product->merk				= $this->input->post('merk');







		$product->description		= '-';







		$product->spesification		= $this->input->post('spesification');







		$product->value				= $this->input->post('value');







		$product->kode_rak			= $this->input->post('kode_rak');







		$product->status_customcode	= $this->input->post('status_customcode');











		if ($product->save()) {















			$filename 	= seo($this->input->post('name'));















			$upload 	= $this->upload_files(__DIR__ . '/../../public_html/images/barang', $_FILES['image'], $filename);







			if ($upload['auth']	== false) {







				$product->delete();







				foreach ($upload['msg'] as $key => $value) {







					remFile(__DIR__ . '/../../public_html/images/barang/' . $value['file_name']);

				}







				echo goResult(false, 'Opss! Gambar Product Tidak Terupload');







				return;

			}















			foreach ($upload['msg'] as $key => $value) {







				$image 					= new BarangImageModel;







				$image->id_barang 		= $product->id;







				$image->image 			= $value['file_name'];







				$image->save();

			}















			$image 						= BarangImageModel::where('id_barang', $product->id)->first();















			if ($image) {







				$product->image 		= $image->image;







				$product->save();

			}















			$price 						= new BarangPriceModel;







			$price->id_barang			= $product->id;







			$price->price_list			= 0;







			$price->cust_new			= 0;







			$price->cust_loyal			= 0;







			$price->cust_vip			= 0;







			$price->reseller			= 0;







			$price->reseller_vip		= 0;







			$price->save();















			$gudang 					= GudangModel::where('status', '1')->orderBy('kode_bee', 'asc')->get();







			foreach ($gudang as $key => $value) {







				$productStock 				= new BarangstokModel;







				$productStock->id_barang 	= $product->id;







				$productStock->id_gudang 	= $value->id;







				$productStock->stok 		= 0;







				$productStock->save();

			}











			$data['auth'] 				= true;







			$data['msg'] 				= 'Spesifikasi telah berhasil dibuat';







			$data['id'] 				= $product->id;







			$data['producttime'] 		= tgl_indo($product->created_at);







			$data['productcode'] 		= $product->new_kode;







			$data['productcategory'] 	= $product->id_category;







			$data['productname'] 		= $product->name;







			echo toJson($data);







			return;

		} else {







			echo goResult(false, 'Spesifikasi gagal dibuat');







			return;

		}

	}







	//API Get Item Rusak



	public function item_rusak_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter 			= $this->input->get('name');







		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}







		$productrusak 				= BarangrusakModel::where('kerusakan', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$page 					= $this->uri->segment(5);



		if (!is_numeric($page)) {







			$page 				= $this->input->get('page');

		}















		$paginate				= new Myweb_pagination;







		$total					= count($productrusak);







		$data['productrusak'] 	= BarangrusakModel::take(20)->skip($page * 20)->where('kerusakan', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;



















		$data['nameFilter'] 	= $valuename;







		echo json_encode($data);

	}







	//API FormEdit Item Rusak



	public function item_rusak_formedit_get()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$data['msg'] = true;



		$data['productrusak'] 	= BarangrusakModel::find($id);



		echo json_encode($data);

	}







	//API Get data To tambah Item Rusak



	public function item_rusak_getdata_tambah_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$this->db->select('*');



		$this->db->from('barang');



		$this->db->where('status_deleted', '0');



		$this->db->order_by('new_kode', 'asc');



		$query = $this->db->get();



		$data['barang'] = $query->result();







		$data['gudang'] 			= GudangModel::where('status', '1')->asc()->get();







		echo goResult(true, $data);

	}







	//API Tambah Item Rusak



	public function item_rusak_tambah_post()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['barang'], ['gudang'], ['kerusakan']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$msg = $this->response([



				'success' => false,



				'message' => $validate



			], 401);



			echo json_encode($msg);







			return;

		}















		$productrusak 					= new BarangrusakModel;







		if (!empty($_FILES['image']['name']) && $this->isImage('image') == true) {







			$filename 	= 'BARANGRUSAK__' . date('Ymdhis');







			$upload 	= $this->upload('images/barang_rusak/', 'image', $filename);







			if ($upload['auth']	== false) {







				echo goResult(false, $upload['msg']);







				return;

			}















			$productrusak->bukti_rusak 	= $upload['msg']['file_name'];

		}



		$productrusak->id_barang 		= $this->input->post('barang');







		$productrusak->id_gudang 		= $this->input->post('gudang');







		$productrusak->kerusakan 		= $this->input->post('kerusakan');







		$productrusak->status 			= '1';



		if ($productrusak->save()) {



			$data['auth'] = true;



			$data['msg'] = 'Item rusak berhasil di tambah';



			$data['data'] = $productrusak;



			echo json_encode($data);







			return;

		} else {



			$data['auth'] = false;



			$data['msg'] = 'Item rusak gagal di tambah';



			$data['data'] = $productrusak;



			echo json_encode($data);







			return;

		}

	}



	//API Tambah Solusi Item Rusak



	public function item_rusak_solusi_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['solusi']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$msg = $this->response([



				'success' => false,



				'message' => $validate



			], 401);



			echo json_encode($msg);







			return;

		}



		$id 						= $this->input->post('itemrusak');



		$productrusak 				= BarangrusakModel::find($id);







		if (!$productrusak) {







			$msg = $this->response([



				'succes' => false,



				'message' => 'Id item rusak tidak ada'



			], 401);



			echo json_encode($msg);







			return;

		}







		if (!empty($_FILES['image']['name']) && $this->isImage('image') == true) {







			$filename 	= 'BARANGSOLUSI__' . date('Ymdhis');







			$upload 	= $this->upload('images/barang_rusak/', 'image', $filename);







			if ($upload['auth']	== false) {







				echo json_encode($upload['msg']);







				return;

			}















			if ($productrusak->bukti_solusi != "") {







				remFile(__DIR__ . '/../../public_html/images/barang_rusak/' . $productrusak->bukti_solusi);

			}















			$productrusak->bukti_solusi 	= $upload['msg']['file_name'];

		}



		$productrusak->solusi 		= $this->input->post('solusi');







		if ($productrusak->save()) {



			$data = array(



				'succes' => 'Check Success',



				'data' => $productrusak



			);



			echo json_encode($data);







			return;

		} else {







			echo json_encode('Check gagal');







			return;

		}

	}







	//API Edit Item Rusak



	public function item_rusak_edit_put()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			// Membaca data dari form-data



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;







			// Mendapatkan produk rusak berdasarkan ID



			$productrusak = BarangrusakModel::find($id);







			// Jika produk rusak tidak ditemukan



			if (!$productrusak) {



				echo json_encode("Item rusak tidak ada");



				return;

			}







			// Mengecek apakah ada file yang diunggah dan merupakan gambar



			if (!empty($_FILES['image']['name']) && $this->isImage('image')) {



				$filename = 'BARANGRUSAK__' . date('Ymdhis');



				$upload = $this->upload('images/barang_rusak/', 'image', $filename);







				// Jika upload gagal



				if ($upload['auth'] === false) {



					echo json_encode($upload['msg']);



					return;

				}







				// Menghapus file lama jika sudah ada



				if ($productrusak->bukti_rusak != "") {



					remFile(__DIR__ . '/../../public_html/images/barang_rusak/' . $productrusak->bukti_rusak);

				}







				// Menyimpan nama file baru



				$productrusak->bukti_rusak = $upload['msg']['file_name'];

			}







			// Memperbarui data barang rusak



			$productrusak->id_barang = isset($input_data['barang']) ? $input_data['barang'] : null;



			$productrusak->id_gudang = isset($input_data['gudang']) ? $input_data['gudang'] : null;



			$productrusak->kerusakan = isset($input_data['kerusakan']) ? $input_data['kerusakan'] : null;







			// Menyimpan perubahan



			if ($productrusak->save()) {



				echo json_encode('Item rusak berhasil di edit');



				return;

			} else {



				echo json_encode('Item rusak gagal di edit');



				return;

			}

		} else {



			$rules = [







				'required' 	=> [







					['barang'], ['gudang'], ['kerusakan']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo goResult(false, $validate->msg);







				return;

			}











			$id = $this->input->post('id');



			$productrusak 				= BarangrusakModel::find($id);







			if (!$productrusak) {







				echo json_encode("Item rusak tidak ada");







				return;

			}















			if (!empty($_FILES['image']['name']) && $this->isImage('image') == true) {







				$filename 	= 'BARANGRUSAK__' . date('Ymdhis');







				$upload 	= $this->upload('images/barang_rusak/', 'image', $filename);







				if ($upload['auth']	== false) {







					echo json_encode($upload['msg']);







					return;

				}















				if ($productrusak->bukti_rusak != "") {







					remFile(__DIR__ . '/../../public_html/images/barang_rusak/' . $productrusak->bukti_rusak);

				}















				$productrusak->bukti_rusak 	= $upload['msg']['file_name'];

			}















			$productrusak->id_barang 		= $this->input->post('barang');







			$productrusak->id_gudang 		= $this->input->post('gudang');







			$productrusak->kerusakan 		= $this->input->post('kerusakan');















			if ($productrusak->save()) {







				echo json_encode(true, 'Item rusak berhasil di edit');







				return;

			} else {







				echo json_encode(false, 'Item rusak gagal di edit');







				return;

			}

		}

	}







	//API Delete Item Rusak



	public function item_rusak_hapus_delete()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$productrusak 				= BarangrusakModel::find($id);







		if (!$productrusak) {







			echo goResult(false, 'Maaf, item rusak tidak ada');







			return;

		}















		if ($productrusak->status == 0) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Id sudah dihapus'



			], 401);







			echo json_encode($msg);



			return;

		} else {



			$productrusak->status 		= '0';







			if ($productrusak->save()) {



				$data = array(



					'msg' => 'Data Berhasil Dihapus',



					'id' => $productrusak->id



				);



				echo json_encode($data);

			}

		}

	}







	//API Get MQQ Item



	public function mqq_item_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 			= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}















		$productmoq 			= BarangmoqModel::where('status', '1')->desc()->get();







		$idproductmoq 			= array();







		foreach ($productmoq as $key => $value) {







			$idproductmoq[] 	= $value->id_barang;

		}















		$product 				= BarangModel::whereIn('id', $idproductmoq)->where('name', 'like', '%' . $valuename . '%')->orWhere('new_kode', 'like', '%' . $valuename . '%')->orderBy('new_kode', 'asc')->get();



		$idproduct 				= array();







		foreach ($product as $key => $value) {



			// dd($value->id);







			$idproduct[] 		= $value->id;

		}















		$fixsearch 				= BarangmoqModel::whereIn('id_barang', $idproduct)->where('status', '1')->desc()->get();















		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 				= $this->input->get('page');

		}















		$paginate				= new Myweb_pagination;















		$total					= count($fixsearch);







		$data['productmoq'] 	= BarangmoqModel::take(20)->skip($page * 20)->whereIn('id_barang', $idproduct)->where('status', '1')->desc()->get();



		$data['productmoq']->pluck('barang.name');







		$data['numberpage'] 	= $page * 20;







		// $data['pagination'] 	= $paginate->paginate(base_url('teknisi/productmoq/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 	= $valuename;



		echo json_encode($data);

	}







	//API Formulir Edit MQQ Item



	public function mqq_item_formulir_edit_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$productmoq	= BarangmoqModel::find($id);



		$data['msg'] = true;



		$data['barang'] 			= BarangModel::where('status_deleted', '0')->where('id', $productmoq->id_barang)->orderBy('new_kode', 'asc')->get();



		$data['productmoq'] = $productmoq;







		echo json_encode($data);

	}



	//API Get Data To tambah MQQ Item



	public function mqq_item_getdata_tambah_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$this->db->select('*');



		$this->db->from('barang');



		$this->db->where('status_deleted', '0');



		$this->db->order_by('new_kode', 'asc');



		$query = $this->db->get();



		$data['barang'] = $query->result();



		echo goResult(true, $data);

	}



	//API Tambah MQQ Item



	public function mqq_item_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['barang'], ['moq']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$msg = $this->response([



				'success' => false,



				'msg' => $validate



			], 401);



			echo json_encode($msg);







			return;

		}















		$productmoq 				= new BarangmoqModel;







		$productmoq->id_barang 		= $this->input->post('barang');







		$productmoq->moq 			= $this->input->post('moq');







		$productmoq->status 		= '1';















		if ($productmoq->save()) {



			$data = array(



				'msg' => 'MQQ item berhasil di tambah',



				'data' => $productmoq



			);



			echo json_encode($data);







			return;

		} else {







			echo json_encode('MOQ item gagal di tambah');







			return;

		}

	}







	//API Delete MQQ Item



	public function mqq_item_hapus_delete()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$productmoq 				= BarangmoqModel::find($id);







		if (!$productmoq) {







			echo json_encode('Maaf, MOQ item tidak ada');







			return;

		}







		if ($productmoq->status == 0) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Id sudah dihapus'



			], 401);







			echo json_encode($msg);



			return;

		} else {







			$productmoq->status 		= '0';







			$productmoq->save();











			$data = array(



				'msg' => 'Data anda berhasil dihapus',



				'data' => $productmoq



			);



			echo json_encode($data);



			return;

		}

	}







	//API Edit MQQ Item



	public function mqq_item_edit_put()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$productmoq 				= BarangmoqModel::find($id);







			if (!$productmoq) {







				echo json_encode("MOQ item tidak ada");







				return;

			}







			$productmoq->id_barang 		= isset($input_data['barang']) ? $input_data['barang'] : null;







			$productmoq->moq 			= isset($input_data['moq']) ? $input_data['moq'] : null;















			if ($productmoq->save()) {



				$data = array(



					'msg' => 'MQQ item berhasi di edit',



					'data' => $productmoq



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('MOQ item gagal di edit');







				return;

			}

		} else {











			$rules = [







				'required' 	=> [







					['barang'], ['moq']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {



				$response = $this->response([



					'success' => false,



					'message' => $validate



				], 401);







				echo json_encode($response);







				return;

			}















			$productmoq 				= BarangmoqModel::find($id);







			if (!$productmoq) {







				echo json_encode("MOQ item tidak ada");







				return;

			}















			$productmoq->id_barang 		= $this->input->post('barang');







			$productmoq->moq 			= $this->input->post('moq');















			if ($productmoq->save()) {



				$data = array(



					'msg' => 'MQQ Item berhasil di edit',



					'data' => $productmoq



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('MOQ item gagal di edit');







				return;

			}

		}

	}







	//API GET Master Item Group



	public function master_item_group_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 			= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}















		$productgroup 				= BaranggroupModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 				= $this->input->get('page');

		}















		$paginate				= new Myweb_pagination;















		$total					= count($productgroup);







		$data['productgroup'] 	= BaranggroupModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;











		$data['nameFilter'] 	= $valuename;



		echo json_encode($data);

	}







	//API Tambah Master Item Group



	public function master_item_group_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			$msg =  $this->response([



				'success' => false,



				'message' => $validate



			], 401);



			return;



			echo json_encode($msg);



			return;

		}



		$productgroup 						= new BaranggroupModel;







		$productgroup->name 				= $this->input->post('name');







		$productgroup->pembelian 			= $this->input->post('pembelian');







		$productgroup->hpp 					= $this->input->post('hpp');







		$productgroup->penjualan 			= $this->input->post('penjualan');







		$productgroup->retur_penjualan 		= $this->input->post('retur_penjualan');







		$productgroup->status 				= '1';















		if ($productgroup->save()) {



			$data = array(



				'msg' => 'Item group berhasil di tambah',



				'data' => $productgroup



			);



			echo json_encode($data);







			return;

		} else {







			echo json_encode('Item group gagal di tambah');







			return;

		}

	}







	//API Edit Formulir Item Group



	public function master_item_group_formuliredit_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['productgroup'] 	= BaranggroupModel::find($id);



		if ($data['productgroup']) {







			echo json_encode($data);



			return;

		}



		$msg = $this->response([



			'success' => false,



			'message' => 'Id tidak ada'



		], 401);



		return;



		echo json_encode($msg);

	}







	//API Edit Item Group



	public function master_item_group_edit_put()



	{







		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$productgroup 				= BaranggroupModel::find($id);







			if (!$productgroup) {







				echo json_encode("Item group tidak ada");







				return;

			}







			// $productgroup->name 				= $this->input->post('name');



			$productgroup->name 				= isset($input_data['name']) ? $input_data['name'] : null;







			// $productgroup->pembelian 			= $this->input->post('pembelian');



			$productgroup->pembelian 			= isset($input_data['pembelian']) ? $input_data['pembelian'] : null;







			// $productgroup->hpp 					= $this->input->post('hpp');



			$productgroup->hpp 					= isset($input_data['hpp']) ? $input_data['hpp'] : null;







			// $productgroup->penjualan 			= $this->input->post('penjualan');



			$productgroup->penjualan 			= isset($input_data['penjualan']) ? $input_data['penjualan'] : null;







			// $productgroup->retur_penjualan 		= $this->input->post('retur_penjualan');



			$productgroup->retur_penjualan 		= isset($input_data['retur_penjualan']) ? $input_data['retur_penjualan'] : null;







			if ($productgroup->save()) {







				$data = array(



					'msg' => 'Item group berhasil di edit',



					'data' => $productgroup



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('Item group gagal di edit');







				return;

			}

		} else {







			$rules = [







				'required' 	=> [







					['name']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo json_encode($validate->msg);







				return;

			}











			$id = $this->input->post('id');



			$productgroup 				= BaranggroupModel::find($id);







			if (!$productgroup) {







				echo json_encode("Item group tidak ada");







				return;

			}















			$productgroup->name 				= $this->input->post('name');







			$productgroup->pembelian 			= $this->input->post('pembelian');







			$productgroup->hpp 					= $this->input->post('hpp');







			$productgroup->penjualan 			= $this->input->post('penjualan');







			$productgroup->retur_penjualan 		= $this->input->post('retur_penjualan');















			if ($productgroup->save()) {







				echo json_encode('Item group berhasil di edit');







				return;

			} else {







				echo json_encode('Item group gagal di edit');







				return;

			}

		}

	}







	//API Delete Item Group



	public function master_item_group_hapus_delete()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));







		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$id = $this->input->get('id');



		$productgroup 				= BaranggroupModel::find($id);







		if (!$productgroup) {



			$msg =   $this->response([



				'success' => false,



				'msg' => $productgroup



			], 401);



			echo json_encode($msg);







			return;

		}



		if ($productgroup->status == 0) {



			$msg =   $this->response([



				'success' => false,



				'msg' => 'Id sudah dihapus'



			], 401);



			echo json_encode($msg);







			return;

		} else {











			$productgroup->status 		= '0';







			$productgroup->save();







			echo json_encode('Data anda berhasil dihapus');







			return;

		}

	}







	//API Get Master Kategori Item



	public function master_kategori_item_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$productcategory 			= BarangcategoryModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($productcategory);







		$data['productcategory'] 	= BarangcategoryModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/productcategory/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 		= $valuename;







		echo json_encode($data);

	}







	//API Get Formuliredit Kategori Item



	public function master_kategori_item_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['productcategory'] 		= BarangcategoryModel::find($id);



		if ($data['productcategory']) {







			echo json_encode($data);

		} else {



			echo json_encode($this->response([



				'success' => false,



				'msg' => 'Id tidak ada'



			]));

		}

	}







	//API Tambah Kategori Item



	public function master_kategori_item_tambah_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$productcategory 					= new BarangcategoryModel;







		$productcategory->name 				= $this->input->post('name');







		$productcategory->status 			= '1';















		if ($productcategory->save()) {







			echo json_encode(array(



				'msg' => 'Kategori item berhasil di tambah',



				'data' => $productcategory



			));







			return;

		} else {







			echo goResult(false, 'Kategori item gagal di tambah');







			return;

		}

	}







	//API Edit Kategori Item



	public function master_kategori_item_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$productcategory 					= BarangcategoryModel::find($id);







			if (!$productcategory) {







				echo json_encode('Produk kategori tidak ada');







				return;

			}







			$productcategory->name 				= isset($input_data['name']) ? $input_data['name'] : null;



			if ($productcategory->save()) {



				$data = array(



					'msg' => 'Kategori item berhasil di edit',



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('Kategori item gagal di edit');







				return;

			}

		} else {



			$rules = [







				'required' 	=> [







					['name']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo json_encode($validate);







				return;

			}



			$id = $this->input->post('id');



			$productcategory 					= BarangcategoryModel::find($id);







			if (!$productcategory) {







				echo json_encode("Produk kategori tidak ada");







				return;

			}







			$productcategory->name 				= $this->input->post('name');







			if ($productcategory->save()) {







				echo json_encode('Kategori item berhasil di edit');







				return;

			} else {







				echo json_encode('Kategori item gagal di edit');







				return;

			}

		}

	}







	//API Delete Kategori Item



	public function master_kategori_item_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$productcategory 					= BarangcategoryModel::find($id);







		if (!$productcategory) {







			echo json_encode('Maaf, kategori item tidak ada');







			return;

		}







		if ($productcategory->status == 0) {



			echo json_encode('Id sudah terhapus');

		} else {







			$productcategory->status 			= '0';







			$productcategory->save();







			echo json_encode(



				array(



					'msg' => 'Data anda berhasil dihapus'







				)



			);







			return;

		}

	}







	//API Get SubKategori Item



	public function master_subkategori_item_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$productsubcategory 			= BarangsubcategoryModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;







		$total						= count($productsubcategory);







		$data['productsubcategory'] = BarangsubcategoryModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();



		$data['productsubcategory']->pluck('category.name');



		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/productsubcategory/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 		= $valuename;



		echo json_encode($data);

	}







	//API Get Formedit SubKategori Item



	public function master_subkategori_item_editformulir_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['productsubcategory'] 	= BarangsubcategoryModel::find($id);



		$data['category'] 			= BarangcategoryModel::where('status', '1')->where('id', $data['productsubcategory']->id_category)->get();







		if (!$data['productsubcategory']) {



			$msg =   $this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			echo json_encode($msg);



			return;

		}



		echo json_encode($data);

	}



	//API Get Kategori To tambah subkategori



	public function master_subkategori_item_kategori_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['category'] 			= BarangcategoryModel::where('status', '1')->asc()->get();



		echo goResult(true, $data);

	}







	//API Tambah Subkategori Item



	public function master_subkategori_item_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['category'], ['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo json_encode($this->response([



				'success' => false,



				'message' => $validate



			]));







			return;

		}















		$productsubcategory 					= new BarangsubcategoryModel;







		$productsubcategory->id_category 		= $this->input->post('category');







		$productsubcategory->name 				= $this->input->post('name');







		$productsubcategory->status 			= '1';















		if ($productsubcategory->save()) {







			echo json_encode(



				array(



					'msg' =>	'Sub kategori item berhasil di tambah',



					'data' => $productsubcategory



				)



			);







			return;

		} else {







			echo json_encode('Sub kategori item gagal di tambah');







			return;

		}

	}







	//API Delete Subkategori Item



	public function master_subkategori_item_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$productsubcategory = BarangsubcategoryModel::find($id);







		if (!$productsubcategory) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;



			echo json_encode($msg);

		}



		if ($productsubcategory->status == 0) {



			echo json_encode('Id sudah dihapus');

		} else {







			$productsubcategory->status 			= '0';







			$productsubcategory->save();







			$data = array(



				'msg' => 'Data anda berhasil dihapus',



				'data' => $productsubcategory->name



			);



			echo json_encode($data);

		}

	}







	//API Edit Subkategori Item



	public function master_subkategori_item_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			if (!$id) {



				$msg = $this->response([



					'msg' => 'Id null'



				], 401);



				return;

			}







			$productsubcategory = BarangsubcategoryModel::find($id);







			if (!$productsubcategory) {



				$msg = $this->response([



					'msg' => 'Id tidak ada'



				], 401);



				return;

			}



			$productsubcategory->name = isset($input_data['name_subcategory']) ? $input_data['name_subcategory'] : null;



			$productsubcategory->id_category = isset($input_data['category']) ? $input_data['category'] : null;



			if ($productsubcategory->save()) {







				$data = array(



					'msg' => 'sub kategori item berhasi di edit',



					'data' => $productsubcategory



				);



				echo json_encode($data);







				return;

			} else {







				echo json_encode('Sub kategori item gagal di edit');







				return;

			}

		} else {







			$rules = [







				'required' 	=> [







					['category'], ['name_subcategory']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo goResult(false, $validate->msg);







				return;

			}











			$id = $this->input->post('id');



			$productsubcategory 					= BarangsubcategoryModel::find($id);







			if (!$productsubcategory) {







				echo goResult(false, "Produk sub kategori tidak ada");







				return;

			}















			$productsubcategory->name 				= $this->input->post('name_subcategory');







			$productsubcategory->id_category 		= $this->input->post('category');















			if ($productsubcategory->save()) {







				echo goResult(true, 'Sub kategori item berhasil di edit');







				return;

			} else {







				echo goResult(false, 'Sub kategori item gagal di edit');







				return;

			}

		}

	}







	//API Get Data Cabang



	public function master_cabang_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$nameFilter 			= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}















		$cabang 				= CabangModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 				= $this->input->get('page');

		}















		$paginate				= new Myweb_pagination;















		$total					= count($cabang);







		$data['cabang'] 		= CabangModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;







		// $data['pagination'] 	= $paginate->paginate(base_url('teknisi/cabang/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 	= $valuename;







		echo json_encode($data);



		return;

	}







	//API Get formulir edit data cabang



	public function master_cabang_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$id = $this->input->get('id');



		if (!$id) {



			$msg = $this->response([



				'auth' => true,



				'msg' => 'Id null'



			]);



			echo json_encode($msg);



			return;

		}











		$data['mastercabang'] = CabangModel::find($id);



		if (!$data['mastercabang']) {



			$msg = $this->response([



				'auth' => true,



				'msg' => 'Id Tidak ada'



			]);



			echo json_encode($msg);



			return;

		}



		echo json_encode($data);



		return;

	}







	//API Tambah Data cabang



	public function master_cabang_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo json_encode(



				$this->response([



					'success' => false,



					'message' => $validate



				], 401)



			);







			return;

		}







		$cabang 				= new CabangModel;







		$cabang->name 			= $this->input->post('name');







		$cabang->status 		= '1';















		if ($cabang->save()) {



			$data = array(



				'msg' => 'Cabang berhasil di tambah',



				'data' => $cabang



			);



			echo json_encode($data);







			return;

		} else {



			$data = array(



				'msg' => 'Cabang gagal di tambah',



			);



			echo json_encode($data);







			return;

		}

	}







	//API Delete Data Cabang



	public function master_cabang_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$cabang 				= CabangModel::find($id);







		if (!$cabang) {







			echo json_encode('Maaf, cabang tidak ada');







			return;

		}







		if ($cabang->status != 0) {











			$cabang->status 		= '0';







			$cabang->save();















			echo json_encode(array(



				'msg' => 'Cabang berhasil dihapus',



				'id' => $cabang->id



			));







			return;

		}



		echo json_encode('Cabang telah dihapus');



		return;

	}







	//API Edit Data Cabang



	public function master_cabang_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			if (!$id) {



				$msg = $this->response([



					'msg' => 'Id null'



				], 401);



				return;

			}











			$cabang 				= CabangModel::find($id);



			// dd($cabang->name);



			if (!$cabang) {







				echo json_encode(array(



					'msg' => 'Cabang tidak ada'



				));







				return;

			}















			$cabang->name 			= isset($input_data['name_cabang']) ? $input_data['name_cabang'] : null;















			if ($cabang->save()) {







				echo json_encode(array(



					'msg' => 'Cabang berhasil di edit',



					'data' => $cabang



				));







				return;

			} else {







				echo json_encode('Cabang gagal di edit');







				return;

			}

		} else {



			$id = $this->input->post('id');



			$rules = [







				'required' 	=> [







					['name_cabang']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo goResult(false, $validate->msg);







				return;

			}















			$cabang 				= CabangModel::find($id);







			if (!$cabang) {







				echo goResult(false, "Cabang tidak ada");







				return;

			}















			$cabang->name 			= $this->input->post('name_cabang');















			if ($cabang->save()) {







				echo goResult(true, 'Cabang berhasil di edit');







				return;

			} else {







				echo goResult(false, 'Cabang gagal di edit');







				return;

			}

		}

	}







	//API Data Gudang



	public function master_gudang_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$gudang 					= GudangModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($gudang);







		$data['gudang'] 			= GudangModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/gudang/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);



		$data['nameFilter'] 		= $valuename;







		echo json_encode($data);

	}







	//API Edit Formulir Data Gudang



	public function master_gudang_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (!$id) {



			$msg = $this->response([



				'auth' => false,



				'msg' => 'Id null'



			], 401);



			echo json_encode($msg);



			return;

		}



		$data['msg'] = 'success';



		$data['gudang'] 				= GudangModel::find($id);







		if (!$data['gudang']) {



			$msg = $this->response([



				'auth' => false,



				'msg' => 'Id tidak ada'



			], 401);



			echo json_encode($msg);



			return;

		} else {







			echo json_encode($data);



			return;

		}

	}







	//API Tambah Data Gudang



	public function master_gudang_tambah_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$product 					= BarangModel::where('status_deleted', '0')->asc()->get();















		$rules = [







			'required' 	=> [







				['kode'], ['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$gudang 					= new GudangModel;







		$gudang->kode_bee 			= $this->input->post('kode');







		$gudang->name 				= $this->input->post('name');







		$gudang->address 			= $this->input->post('address');







		$gudang->status 			= '1';











		$this->db->trans_begin();



		if ($gudang->save()) {















			foreach ($product as $key => $value) {







				$productStock 				= new BarangstokModel;







				$productStock->id_barang 	= $value->id;







				$productStock->id_gudang 	= $gudang->id;







				$productStock->stok 		= 0;







				$productStock->save();

			}















			echo json_encode(array(



				'msg' => 'Gudang berhasil di tambah',



				'data' => $productStock







			));







			return;

		} else {







			echo json_encode('Gudang gagal di tambah');







			$this->db->trans_rollback();



			return;

		}

	}







	//API Delete Data Gudang



	public function master_gudang_hapus_delete()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		if (!$id) {



			$msg = $this->response([



				'msg' => 'Id null'



			], 404);



			echo json_encode($msg);







			return;

		}







		$gudang 						= GudangModel::find($id);







		if (!$gudang) {



			$msg = $this->response([



				'msg' => 'Maaf, gudang tidak ada'



			], 404);



			echo json_encode($msg);







			return;

		}











		if ($gudang->status != 0) {







			$gudang->status 				= '0';







			$gudang->save();







			echo json_encode(



				array(



					'auth' => true,



					'msg' => 'Gudang anda berhasil dihapus'



				)



			);



			return;

		} else {



			echo json_encode($this->response([



				'msg' => 'Gudang sudah dihapus'



			], 401));



			return;

		}

	}







	//API Edit Data Gudang



	public function master_gudang_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			// dd($id);



			$gudang 					= GudangModel::find($id);



			if (!$gudang) {







				echo json_encode(



					$this->response([



						'msg' => 'Gudang tidak ada',



					], 404)



				);







				return;

			}



			$gudang->kode_bee 			= isset($input_data['kode']) ? $input_data['kode'] : null;







			$gudang->name 				= isset($input_data['name']) ? $input_data['name'] : null;







			$gudang->address 			= isset($input_data['address']) ? $input_data['address'] : null;



			$this->db->trans_begin();



			if ($gudang->save()) {







				echo json_encode(array(



					'msg' => 'Gudang berhasil diedit',



					'data' => $gudang



				));







				return;

			} else {







				echo json_encode($this->response([



					'msg' => 'Gudang gagal diedit',



				], 401));



				$this->db->trans_rollback();



				return;

			}

		} else {



			$rules = [







				'required' 	=> [







					['kode'], ['name']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo goResult(false, $validate->msg);







				return;

			}











			$id = $this->input->post('id');



			$gudang 					= GudangModel::find($id);







			if (!$gudang) {







				echo goResult(false, "Gudang tidak ada");







				return;

			}















			$gudang->kode_bee 			= $this->input->post('kode');







			$gudang->name 				= $this->input->post('name');







			$gudang->address 			= $this->input->post('address');







			$this->db->trans_begin();



			if ($gudang->save()) {







				echo json_encode(array(



					'msg' => 'Gudang berhasil diedit',



					'data' => $gudang



				));







				return;

			} else {







				echo json_encode($this->response([



					'msg' => 'Gudang gagal diedit',



				], 401));



				$this->db->trans_rollback();



				return;

			}

		}

	}







	//API Get Data termin



	public function master_termin_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter 			= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}















		$termin 				= TerminModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 				= 0;

		}















		$paginate				= new Myweb_pagination;















		$total					= count($termin);







		$data['termin'] 		= TerminModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;







		// $data['pagination'] 	= $paginate->paginate(base_url('teknisi/termin/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 	= $valuename;







		echo json_encode($data);

	}







	//API Get Formuliredit Data termin



	public function master_termin_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (!$id) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			echo json_encode($msg);



			return;

		}



		$data['termin'] 		= TerminModel::find($id);



		$data['msg'] = true;



		if (!$data['termin']) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Termin tidak ada'



			], 401);



			echo json_encode($msg);



			return;

		}



		echo json_encode($data);



		return;

	}







	//API Delete Data termin



	public function master_termin_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');







		if (!$id) {







			echo json_encode($this->response([



				'success' => false,



				'message' => 'Id null'



			], 404));







			return;

		}







		$termin 				= TerminModel::find($id);







		if (!$termin) {







			echo json_encode($this->response([



				'success' => false,



				'message' => 'Termin tidak ada'



			], 404));







			return;

		}







		if ($termin->status != 0) {







			$termin->status 		= '0';







			$termin->save();







			echo json_encode('Termin berhasil dihapus');



			return;

		}







		echo json_encode($this->response([



			'success' => false,



			'message' => 'Termin sudah dihapus'



		], 404));



		return;

	}



	//API Tambah Data termin



	public function master_termin_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			http_response_code(401);



			echo goResult(false, $validate->msg);







			return;

		}















		$termin 				= new TerminModel;







		$termin->name 			= $this->input->post('name');







		$termin->status 		= '1';















		if ($termin->save()) {



			$data = array(



				'msg' => 'Termin berhasil di tambah',



				'data' => $termin



			);



			echo json_encode($data);







			return;

		} else {







			echo json_encode('Termin gagal di tambah');







			return;

		}

	}







	//API Edit Data termin



	public function master_termin_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			if (!$id) {







				echo json_encode($this->response([



					'msg' => 'Id null'



				], 404));







				return;

			}



			$termin 				= TerminModel::find($id);



			if (!$termin) {







				echo json_encode($this->response([



					'msg' => 'Termin tidak ada'



				], 404));







				return;

			}







			$termin->name 			= isset($input_data['name']) ? $input_data['name'] : null;







			if ($termin->save()) {







				echo json_encode(



					array(



						'msg' => 'Termin berhasil di edit',



						'data' => $termin



					)



				);







				return;

			} else {







				echo json_encode('Termin gagal di edit');







				return;

			}

		} else {



			$rules = [







				'required' 	=> [







					['name']







				]







			];



			$id = $this->input->post('id');



			if (!$id) {







				echo json_encode($this->response([



					'msg' => 'Id null'



				], 404));







				return;

			}



			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo json_encode($this->response([



					'msg' => 'Termin tidak ada'



				], 404));







				return;

			}







			$termin 				= new TerminModel;







			$termin->name 			= $this->input->post('name');







			$termin->status 		= '1';















			if ($termin->save()) {







				echo json_encode(



					array(



						'msg' => 'Termin berhasil di edit',



						'data' => $termin



					)



				);







				return;

			} else {







				echo json_encode('Termin gagal di tambah');







				return;

			}

		}

	}







	//API Tambah Master Mata Uang



	public function master_matauang_tambah_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$rules = [







			'required' 	=> [







				['kode'], ['name'], ['simbol']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {



			http_response_code(401);



			echo goResult(false, $validate->msg);







			return;

		}















		$matauang 				= new MatauangModel;







		$matauang->kode 		= $this->input->post('kode');







		$matauang->name 		= $this->input->post('name');







		$matauang->simbol 		= $this->input->post('simbol');







		$matauang->status 		= '1';















		if ($matauang->save()) {







			echo json_encode(array('msg' => 'Mata uang berhasil di tambah', 'data' => $matauang));







			return;

		} else {







			echo goResult(false, 'Mata uang gagal di tambah');







			return;

		}

	}







	//API Get Master Mata Uang



	public function master_matauang_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter 			= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 			= '';

		} else {







			$valuename 			= $nameFilter;

		}















		$matauang 				= MatauangModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 				= $this->input->get('page');

		}















		$paginate				= new Myweb_pagination;















		$total					= count($matauang);







		$data['matauang'] 		= MatauangModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;







		// $data['pagination'] 	= $paginate->paginate(base_url('teknisi/matauang/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);















		$data['nameFilter'] 	= $valuename;







		echo json_encode($data);

	}







	//API Get Formuliredit Mata Uang



	public function master_matauang_formuliredit_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (!$id) {







			$msg = $this->response([



				'success' => false,



				'message' => 'Id null'



			], 404);



			return;



			echo json_encode($msg);

		}



		$data['msg'] = true;



		$data['matauang'] 		= MatauangModel::find($id);



		if (!$data['matauang']) {







			$msg = $this->response([



				'success' => false,



				'message' => 'Mata uang tidak ditemukan'



			], 404);



			return;



			echo json_encode($msg);

		}



		echo json_encode($data);

	}







	//API Delete Master Mata uang



	public function master_matauang_hapus_delete()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');







		if (!$id) {







			echo json_encode($this->response([



				'success' => false,



				'message' => 'Id null'



			], 404));







			return;

		}







		$matauang 				= MatauangModel::find($id);







		if (!$matauang) {







			echo json_encode($this->response([



				'success' => false,



				'message' => 'Mata Uang tidak ada'



			], 404));







			return;

		}







		if ($matauang->status != 0) {







			$matauang->status 		= '0';







			$matauang->save();







			echo json_encode('Mata Uang berhasil dihapus');



			return;

		}







		echo json_encode($this->response([



			'success' => false,



			'message' => 'Mata Uang sudah dihapus'



		], 404));



		return;

	}







	//API Get Master Rak



	public function master_rak_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$codeFilter 				= $this->input->get('code');















		if (!$codeFilter) {







			$valuecode 				= '';

		} else {







			$valuecode 				= $codeFilter;

		}















		$rak 						= RakModel::where('code', 'like', '%' . $valuecode . '%')->where('status', '1')->asc()->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($rak);







		$data['rak'] 				= RakModel::take(20)->skip($page * 20)->where('code', 'like', '%' . $valuecode . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/rak/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['codeFilter'] 		= $valuecode;







		echo json_encode($data);

	}







	//API Tambah Master Rak



	public function master_rak_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['kode']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$rak 					= new RakModel;







		$rak->code 				= $this->input->post('kode');







		$rak->status 			= '1';















		if ($rak->save()) {







			echo goResult(true, 'Rak berhasil di tambah');







			return;

		} else {







			echo goResult(false, 'Rak gagal di tambah');







			return;

		}

	}







	//API Delete Master Rak



	public function master_rak_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (!$id) {







			echo goResult(false, 'Id Null');







			return;

		}



		$rak 						= RakModel::find($id);







		if (!$rak) {







			echo goResult(false, 'Maaf, rak tidak ada');







			return;

		}







		if ($rak->status != 0) {







			$rak->status 				= '0';







			$rak->save();







			echo goResult(true, 'Data rak anda berhasil dihapus');







			return;

		} else {



			echo goResult(false, 'Data rak anda sudah dihapus');







			return;

		}

	}







	//API Formulir Edit Master Rak



	public function master_rak_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id Null'



			], 404);



			return;

		}







		$data['rak'] 				= RakModel::find($id);



		if (empty($data['rak'])) {







			$msg = $this->response([



				'success' => false,



				'message' => 'Data tidak ditemukan'



			], 404);



			echo json_encode($msg);



			return;

		}



		echo json_encode($data);

	}







	//API Edit Master Rak



	public function master_rak_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			if (!$id) {







				echo goResult(false, "Id null");







				return;

			}



			$rak 					= RakModel::find($id);



			if (!$rak) {







				echo goResult(false, "Rak tidak ada");







				return;

			}







			$rak->code 				= isset($input_data['kode']) ? $input_data['kode'] : null;



			if (!$rak->code) {







				echo goResult(false, "Code tidak ada");



				return;

			}











			if ($rak->save()) {



				$data = array(



					'msg' => 'Rak berhasil di edit',



					'data' => $rak



				);



				echo goResult(true, $data);







				return;

			} else {







				echo goResult(false, 'Rak gagal di edit');







				return;

			}

		} else {



			$rules = [







				'required' 	=> [







					['kode']







				]







			];







			$validate 	= Validation::check($rules, 'post');







			if (!$validate->auth) {







				echo goResult(false, $validate->msg);







				return;

			}



			$id = $this->input->post('id');











			$rak 					= RakModel::find($id);







			if (!$rak) {







				echo goResult(false, "Rak tidak ada");







				return;

			}















			$rak->code 				= $this->input->post('kode');







			if (!$rak->code) {







				echo goResult(false, "Code tidak ada");



				return;

			}







			if ($rak->save()) {







				echo goResult(true, 'Rak berhasil di edit');







				return;

			} else {







				echo goResult(false, 'Rak gagal di edit');







				return;

			}

		}

	}







	//API Master PPN



	// public function master_ppn_get(){







	// }







	//API Get Master Discount



	public function master_discount_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['price_list'] 	= PriceDiscountModel::find(1);







		$data['cust_baru'] 		= PriceDiscountModel::find(2);







		$data['cust_loyal'] 	= PriceDiscountModel::find(3);







		$data['cust_vip'] 		= PriceDiscountModel::find(4);







		$data['reseller'] 		= PriceDiscountModel::find(5);







		$data['reseller_vip'] 	= PriceDiscountModel::find(6);















		$data['price_list_sparepart'] 		= PriceDiscountSparepartModel::find(1);







		$data['cust_baru_sparepart'] 		= PriceDiscountSparepartModel::find(2);







		$data['cust_loyal_sparepart'] 		= PriceDiscountSparepartModel::find(3);







		$data['cust_vip_sparepart'] 		= PriceDiscountSparepartModel::find(4);







		$data['reseller_sparepart'] 		= PriceDiscountSparepartModel::find(5);







		$data['reseller_vip_sparepart'] 	= PriceDiscountSparepartModel::find(6);















		$data['price_list_bahanbaku'] 		= PriceDiscountBahanBakuModel::find(1);







		$data['cust_baru_bahanbaku'] 		= PriceDiscountBahanBakuModel::find(2);







		$data['cust_loyal_bahanbaku'] 		= PriceDiscountBahanBakuModel::find(3);







		$data['cust_vip_bahanbaku'] 		= PriceDiscountBahanBakuModel::find(4);







		$data['reseller_bahanbaku'] 		= PriceDiscountBahanBakuModel::find(5);







		$data['reseller_vip_bahanbaku'] 	= PriceDiscountBahanBakuModel::find(6);







		$array = array(



			'cust_baru' => $data['cust_baru']->discount,



			'cust_loyal' => $data['cust_loyal']->discount,



			'cust_vip' => $data['cust_vip']->discount,



			'reseller' => $data['reseller']->discount,



			'reseller_vip' => $data['reseller_vip']->discount



		);



		$array_sparepart = array(



			'cust_baru_sparepart' => $data['cust_baru_sparepart']->discount,



			'cust_loyal_sparepart' => $data['cust_loyal_sparepart']->discount,



			'cust_vip_sparepart' => $data['cust_vip_sparepart']->discount,



			'reseller_sparepart' => $data['reseller_sparepart']->discount,



			'reseller_vip_sparepart' => $data['reseller_vip_sparepart']->discount



		);







		$array_bahanbaku = array(



			'cust_baru_bahanbaku' => $data['cust_baru_bahanbaku']->discount,



			'cust_loyal_bahanbaku' => $data['cust_loyal_bahanbaku']->discount,



			'cust_vip_bahanbaku' => $data['cust_vip_bahanbaku']->discount,



			'reseller_bahanbaku' => $data['reseller_bahanbaku']->discount,



			'reseller_vip_bahanbaku' => $data['reseller_vip_bahanbaku']->discount



		);







		$data_array = array(



			'data' => array($array, $array_sparepart, $array_bahanbaku)



		);



		echo json_encode($data_array);

	}







	//API Edit Master Discount (Tab Mesin)



	public function master_discount_tambahmesin_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['cust_baru'], ['cust_loyal'], ['cust_vip'], ['reseller'], ['reseller_vip']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$cust_baru 				= PriceDiscountModel::find(2);







		$cust_baru->discount 	= $this->input->post('cust_baru');







		$cust_baru->save();















		$cust_loyal 			= PriceDiscountModel::find(3);







		$cust_loyal->discount 	= $this->input->post('cust_loyal');







		$cust_loyal->save();















		$cust_vip 				= PriceDiscountModel::find(4);







		$cust_vip->discount 	= $this->input->post('cust_vip');







		$cust_vip->save();















		$reseller 				= PriceDiscountModel::find(5);







		$reseller->discount 	= $this->input->post('reseller');







		$reseller->save();















		$reseller_vip 			= PriceDiscountModel::find(6);







		$reseller_vip->discount = $this->input->post('reseller_vip');







		$reseller_vip->save();















		$barang 				= BarangModel::where('id_category', 1)->asc()->get(); //category mesin







		foreach ($barang as $key => $value) {







			$barangId 					= BarangModel::find($value->id);







			$barangId->status_price 	= 'Default';







			$barangId->save();















			if ($value->price == '') {







				$pricedefault = 0;

			} else {







				$pricedefault = $value->price;

			}















			$barangPrice 				= BarangPriceModel::where('id_barang', $value->id)->first();







			if ($barangPrice) {







				$price 					= BarangPriceModel::find($barangPrice->id);







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			} else {







				$price 					= new BarangPriceModel;







				$price->id_barang 		= $value->id;







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			}















			$discount_custom 				= BarangDiscountCustomModel::where('id_barang', $value->id)->first();







			if ($discount_custom) {







				BarangDiscountCustomModel::where('id_barang', $value->id)->delete();

			}

		}















		echo goResult(true, 'Discount Mesin berhasil di ubah');







		return;

	}







	//API Edit Master Discount (Tab Bahan Baku)



	public function master_discount_tambahbahanbaku_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['cust_baru'], ['cust_loyal'], ['cust_vip'], ['reseller'], ['reseller_vip']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$cust_baru 				= PriceDiscountBahanBakuModel::find(2);







		$cust_baru->discount 	= $this->input->post('cust_baru');







		$cust_baru->save();















		$cust_loyal 			= PriceDiscountBahanBakuModel::find(3);







		$cust_loyal->discount 	= $this->input->post('cust_loyal');







		$cust_loyal->save();















		$cust_vip 				= PriceDiscountBahanBakuModel::find(4);







		$cust_vip->discount 	= $this->input->post('cust_vip');







		$cust_vip->save();















		$reseller 				= PriceDiscountBahanBakuModel::find(5);







		$reseller->discount 	= $this->input->post('reseller');







		$reseller->save();















		$reseller_vip 			= PriceDiscountBahanBakuModel::find(6);







		$reseller_vip->discount = $this->input->post('reseller_vip');







		$reseller_vip->save();















		$barang 				= BarangModel::where('id_category', 2)->asc()->get(); //category bahan baku







		foreach ($barang as $key => $value) {







			$barangId 					= BarangModel::find($value->id);







			$barangId->status_price 	= 'Default';







			$barangId->save();















			if ($value->price == '') {







				$pricedefault = 0;

			} else {







				$pricedefault = $value->price;

			}















			$barangPrice 				= BarangPriceModel::where('id_barang', $value->id)->first();







			if ($barangPrice) {







				$price 					= BarangPriceModel::find($barangPrice->id);







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			} else {







				$price 					= new BarangPriceModel;







				$price->id_barang 		= $value->id;







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			}















			$discount_custom 				= BarangDiscountCustomModel::where('id_barang', $value->id)->first();







			if ($discount_custom) {







				BarangDiscountCustomModel::where('id_barang', $value->id)->delete();

			}

		}















		echo goResult(true, 'Discount Bahan Baku berhasil di ubah');







		return;

	}







	//API Edit Master Discount (Tab Sparepart)



	public function master_discount_tambahsparepart_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['cust_baru'], ['cust_loyal'], ['cust_vip'], ['reseller'], ['reseller_vip']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$cust_baru 				= PriceDiscountSparepartModel::find(2);







		$cust_baru->discount 	= $this->input->post('cust_baru');







		$cust_baru->save();















		$cust_loyal 			= PriceDiscountSparepartModel::find(3);







		$cust_loyal->discount 	= $this->input->post('cust_loyal');







		$cust_loyal->save();















		$cust_vip 				= PriceDiscountSparepartModel::find(4);







		$cust_vip->discount 	= $this->input->post('cust_vip');







		$cust_vip->save();















		$reseller 				= PriceDiscountSparepartModel::find(5);







		$reseller->discount 	= $this->input->post('reseller');







		$reseller->save();















		$reseller_vip 			= PriceDiscountSparepartModel::find(6);







		$reseller_vip->discount = $this->input->post('reseller_vip');







		$reseller_vip->save();















		$barang 				= BarangModel::where('id_category', 3)->asc()->get(); //category sparepart







		foreach ($barang as $key => $value) {







			$barangId 					= BarangModel::find($value->id);







			$barangId->status_price 	= 'Default';







			$barangId->save();















			if ($value->price == '') {







				$pricedefault = 0;

			} else {







				$pricedefault = $value->price;

			}















			$barangPrice 				= BarangPriceModel::where('id_barang', $value->id)->first();







			if ($barangPrice) {







				$price 					= BarangPriceModel::find($barangPrice->id);







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			} else {







				$price 					= new BarangPriceModel;







				$price->id_barang 		= $value->id;







				$price->price_list 		= $pricedefault;







				$price->cust_new 		= $pricedefault - ($pricedefault * $this->input->post('cust_baru') / 100);







				$price->cust_loyal 		= $pricedefault - ($pricedefault * $this->input->post('cust_loyal') / 100);







				$price->cust_vip 		= $pricedefault - ($pricedefault * $this->input->post('cust_vip') / 100);







				$price->reseller 		= $pricedefault - ($pricedefault * $this->input->post('reseller') / 100);







				$price->reseller_vip 	= $pricedefault - ($pricedefault * $this->input->post('reseller_vip') / 100);







				$price->save();

			}















			$discount_custom 				= BarangDiscountCustomModel::where('id_barang', $value->id)->first();







			if ($discount_custom) {







				BarangDiscountCustomModel::where('id_barang', $value->id)->delete();

			}

		}















		echo goResult(true, 'Discount Sparepart berhasil di ubah');







		return;

	}







	//API Get Master Ekspedisi



	public function master_ekspedisi_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$ekspedisi 					= EkspedisiModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($ekspedisi);







		$data['ekspedisi'] 			= EkspedisiModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/ekspedisi/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);















		$data['nameFilter'] 		= $valuename;



		echo json_encode($data);

	}







	//API Get Formuliredit Ekspedisi



	public function master_ekspedisi_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['ekspedisi'] 				= EkspedisiModel::find($id);



		if (!$data['ekspedisi']) {



			$msg = $this->response([



				'success' => false,



				'message' => 'Data Ekspedisi Tidak Ada'



			], 404);



			echo json_encode($msg);



			return;

		}



		echo json_encode($data);



		return;

	}







	//API Edit Master Ekspedisi



	public function master_ekspedisi_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {







			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$ekspedisi 						= EkspedisiModel::find($id);







			if (!$ekspedisi) {







				echo goResult(false, "Ekspedisi not found");







				return;

			}















			$ekspedisi->name 				= isset($input_data['name']) ? $input_data['name'] : null;







			$ekspedisi->address 			= isset($input_data['address']) ? $input_data['address'] : null;







			$ekspedisi->contact 			= isset($input_data['contact']) ? $input_data['contact'] : null;















			if ($ekspedisi->save()) {



				$data = array(



					'msg' => 'Ekspedisi success for update',



					'data' => $ekspedisi



				);







				unset($data['data']['id']);



				echo goResult(true, $data);







				return;

			} else {







				echo goResult(false, 'Ekspedisi not success for update');







				return;

			}

		} else {



			echo gorResult(false, 'Method bukan put');

		}

	}







	//API Tambah Master Ekspedisi



	public function master_ekspedisi_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name'], ['address']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$ekspedisi 						= new EkspedisiModel;







		$ekspedisi->name 				= $this->input->post('name');







		$ekspedisi->address 			= $this->input->post('address');







		$ekspedisi->contact 			= $this->input->post('contact');







		$ekspedisi->status 				= '1';















		if ($ekspedisi->save()) {



			$data = array(



				'msg' => 'Ekspedisi success for save',



				'data' => $ekspedisi



			);



			unset($data['data']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Ekspedisi not success for save');







			return;

		}

	}







	//API Delete Master Ekspedisi



	public function master_ekspedisi_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$ekspedisi 						= EkspedisiModel::find($id);







		if (!$ekspedisi) {







			echo goResult(false, 'Ekspedisi not found');







			return;

		}







		if ($ekspedisi->status != 0) {







			$ekspedisi->status 				= '0';







			$ekspedisi->save();











			$msg = array(



				'msg' => 'Ekspedisi success for delete',



				'data' => $ekspedisi



			);



			unset($msg['data']['id']);



			echo goResult(true, $msg);







			return;

		} else {



			echo goResult(false, 'Ekspedisi has been deleted');







			return;

		}

	}







	//API Get Master Status Order Penjualan



	public function master_statusorder_penjualan_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$masterstatusorder 			= MasterstatusorderModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($masterstatusorder);







		$data['masterstatusorder'] 	= MasterstatusorderModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();







		$data['numberpage'] 		= $page * 20;







		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);

	}







	//API Get Formuliredit Master Status Order Penjualan



	public function master_statusorder_penjualan_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['masterstatusorderpenjualan'] = MasterstatusorderModel::find($id);



		echo goResult(false, $data);

	}







	//API Tambah Master Status Order Penjualan 



	public function master_statusorder_penjualan_tambah_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$masterstatusorder 			= new MasterstatusorderModel;







		$masterstatusorder->name 	= $this->input->post('name');







		$masterstatusorder->status 	= '1';















		if ($masterstatusorder->save()) {







			$data = array(



				'msg' => 'Status order success for save',



				'data' => $masterstatusorder



			);



			unset($data['data']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Status order not success for save');







			return;

		}

	}







	//API Delete Master Status Order Penjualan



	public function master_statusorder_penjualan_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$masterstatusorder 			= MasterstatusorderModel::find($id);







		if (!$masterstatusorder) {







			echo goResult(false, 'Status order not found');







			return;

		}







		if ($masterstatusorder->status != 0) {







			$masterstatusorder->status 	= '0';







			$masterstatusorder->save();







			$data = array(



				'msg' => 'Status order success for delete',



				'data' => $masterstatusorder



			);



			unset($data['data']['id']);







			echo goResult(true, $data);







			return;

		}



		echo goResult(false, 'Status order success has been deleted');

	}







	//API Edit Master Status Order Penjualan



	public function master_statusorder_penjualan_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$masterstatusorder 			= MasterstatusorderModel::find($id);







			if (!$masterstatusorder) {







				echo goResult(false, "Status order not found");







				return;

			}



			$name = isset($input_data['name']) ? $input_data['name'] : null;



			if (empty($name)) {



				echo goResult(false, "Name field is required");



				return;

			}



			$masterstatusorder->name 	= $name;















			if ($masterstatusorder->save()) {



				$data = array(



					'msg' => 'Status order success for update',



					'data' => $masterstatusorder



				);



				unset($data['data']['id']);







				echo goResult(true, $data);







				return;

			} else {







				echo goResult(false, 'Status order not success for update');







				return;

			}

		} else {



			echo goResult(false, "Method bukan PUT");

		}

	}







	//API Get Master Board Penjualan



	public function master_board_penjualan_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$penjualanboard 			= PenjualanboardModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($penjualanboard);







		$data['penjualanboard'] 	= PenjualanboardModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/penjualanboard/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 		= $valuename;



		echo goResult(true, $data);

	}







	//API Get Formuliredit Master Board Penjualan



	public function master_board_penjualan_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		// dd($id);



		$data['penjualanboard'] 	= PenjualanboardModel::find($id);



		if ($data['penjualanboard']) {







			echo goResult(true, $data);

		} else {







			echo goResult(false, 'Board penjualan tidak ada');

		}

	}







	//API Tambah Master Board Penjualan



	public function master_board_penjualan_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$penjualanboard 			= new PenjualanboardModel;







		$penjualanboard->name 		= $this->input->post('name');







		$penjualanboard->status 	= '1';















		if ($penjualanboard->save()) {



			$data = array(



				'msg' => 'Board penjualan berhasil di tambah',



				'data' => $penjualanboard



			);



			unset($data['data']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Board penjualan gagal di tambah');







			return;

		}

	}







	//API Delete Master Board Penjualan



	public function master_board_penjualan_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		$penjualanboard 			= PenjualanboardModel::find($id);







		if (!$penjualanboard) {







			echo goResult(false, 'Board penjualan not found');







			return;

		}



		if ($penjualanboard->status != 0) {







			$penjualanboard->status 	= '0';







			$penjualanboard->save();















			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			echo goResult(true, 'Data anda sudah dihapus');







			return;

		}

	}







	//API Edit Master Board Penjualan



	public function master_board_penjualan_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;



			$penjualanboard 			= PenjualanboardModel::find($id);







			$name = isset($input_data['name']) ? $input_data['name'] : null;



			if (!$id) {







				echo goResult(false, "Id null");







				return;

			}



			if (empty($name)) {







				echo goResult(false, 'Name is Required');



				return;

			}



			if (!$penjualanboard) {







				echo goResult(false, "Board penjualan not found");







				return;

			}







			$penjualanboard->name 		= $name;







			if ($penjualanboard->save()) {



				$msg = array(



					'msg' => 'Board penjualan berhasil di edit',



					'data' => $penjualanboard



				);



				unset($msg['data']['id']);



				echo goResult(true, $msg);







				return;

			} else {







				echo goResult(false, 'Board penjualan gagal di edit');







				return;

			}

		} else {



			echo goResult(false, 'Method bukan Put');

		}

	}







	//API Get Master Tag Category Penjualan



	public function master_tag_category_penjualan_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$penjualanoptioncategory 	= PenjualanoptioncategoryModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($penjualanoptioncategory);







		$data['penjualanoptioncategory'] = PenjualanoptioncategoryModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->where('id', '!=', '0')->orderBy('name', 'asc')->get();







		$data['numberpage'] 		= $page * 20;











		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);

	}







	//API Get Formuliredit Master Tag Category Penjualan



	public function master_tag_category_penjualan_formedit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}







		$data['penjualanoptioncategory'] 	= PenjualanoptioncategoryModel::find($id);



		if (empty($data['penjualanoptioncategory'])) {







			$this->response([



				'success' => false,



				'message' => 'Data not found'



			], 401);



			return;

		}



		echo goResult(true, $data);

	}







	//API Tambah Master Tag Category Penjualan



	public function master_tag_category_penjualan_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$namelower 							= strtolower($this->input->post('name'));







		$namespace 							= str_replace(' ', '', $namelower);















		$penjualanoptioncategory 			= new PenjualanoptioncategoryModel;







		$penjualanoptioncategory->name 		= $this->input->post('name');







		$penjualanoptioncategory->namespace = $namespace;







		$penjualanoptioncategory->status 	= '1';















		if ($penjualanoptioncategory->save()) {







			$data = array(



				'msg' => 'Tag category success for save',



				'data' => $penjualanoptioncategory



			);



			unset($data['data']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Tag category not success for save');







			return;

		}

	}







	//API Delete Master Tag Category Penjualan



	public function master_tag_category_penjualan_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$penjualanoptioncategory 			= PenjualanoptioncategoryModel::find($id);







		if (!$penjualanoptioncategory) {







			echo goResult(false, 'Tag category not found');







			return;

		}



		if ($penjualanoptioncategory->status != 0) {







			$penjualanoptioncategory->status 	= '0';







			$penjualanoptioncategory->save();















			echo goResult(true, 'Tag category success for delete');







			return;

		}



		echo goResult(false, 'Tag category has been deleted');







		return;

	}







	//API Edit Master Tag Category Penjualan



	public function master_tag_category_penjualan_edit_put()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;







			if (empty($id)) {







				$this->response([



					'success' => false,



					'message' => 'Id null'



				], 401);



				return;

			}







			$penjualanoptioncategory 			= PenjualanoptioncategoryModel::find($id);







			if (!$penjualanoptioncategory) {







				echo goResult(false, "Tag category not found");







				return;

			}



			$name = isset($input_data['name']) ? $input_data['name'] : null;



			if (!$name) {







				echo goResult(false, "Name is required");







				return;

			}



			$namelower 							= strtolower($name);







			$namespace 							= str_replace(' ', '', $namelower);















			$penjualanoptioncategory->name 		= $name;







			$penjualanoptioncategory->namespace = $namespace;















			if ($penjualanoptioncategory->save()) {



				$data = array(



					'msg' => 'Tag category success for update',



					'data' => $penjualanoptioncategory



				);



				unset($data['data']['id']);



				echo goResult(true, $data);







				return;

			} else {







				echo goResult(false, 'Tag category not success for update');







				return;

			}

		} else {



			echo goResult(false, "Method bukan put");







			return;

		}

	}







	//API Get Master Tag Penjualan



	public function master_tag_penjualan_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$penjualanoption 			= PenjualanoptionModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$paginate					= new Myweb_pagination;















		$total						= count($penjualanoption);







		$data['penjualanoption'] 	= PenjualanoptionModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 		= $page * 20;



		$names = [];



		foreach ($data['penjualanoption'] as $option) {



			$names[] = $option->name;

		}







		$data['nameFilter'] 		= $valuename;







		$data['namecategory'] = $names;



		echo goResult(true, $data);

	}







	//API Get Formuliredit Master Tag Penjualan



	public function master_tag_penjualan_formedit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		$data['penjualanoption'] 	= PenjualanoptionModel::find($id);



		if ($data['penjualanoption']) {







			echo goResult(true, $data);



			return;

		}



		echo goResult(false, 'Data not found');



		return;

	}







	//API Tambah Master Tag Penjualan



	public function master_tag_penjualan_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['name'], ['category']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$penjualanoption 				= new PenjualanoptionModel;







		$penjualanoption->name 			= $this->input->post('name');







		$penjualanoption->id_category 	= $this->input->post('category');







		$penjualanoption->status 	= '1';















		if ($penjualanoption->save()) {



			$data = array(



				'msg' => 'Tag penjualan success for save',



				'data' => $penjualanoption



			);



			unset($data['data']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Tag penjualan not success for save');







			return;

		}

	}







	//API Delete Master Tag Penjualan



	public function master_tag_penjualan_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		$penjualanoption 			= PenjualanoptionModel::find($id);







		if (!$penjualanoption) {







			echo goResult(false, 'Tag penjualan not found');







			return;

		}















		$penjualan 					= PenjualanModel::where('status_deleted', '0')->where('id_opsi', $id)->desc()->get();







		if (count($penjualan) > 0) {







			echo goResult(false, 'Maaf, tag tidak bisa di hapus karena terkoneksi dengan data penjualan');







			return;

		}











		if ($penjualanoption->status != 0) {







			$penjualanoption->status 	= '0';







			$penjualanoption->save();















			echo goResult(true, 'Tag penjualan success for delete');







			return;

		}



		http_response_code(401);



		echo goResult(false, 'Tag penjualan has been deleting');







		return;

	}







	//API Edit Master Tag Penjualan



	public function master_tag_penjualan_edit_put()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {



			parse_str(file_get_contents("php://input"), $input_data);



			$id = isset($input_data['id']) ? $input_data['id'] : null;







			$penjualanoption 			= PenjualanoptionModel::find($id);







			if (!$penjualanoption) {







				echo goResult(false, "Tag penjualan not found");







				return;

			}











			$name = isset($input_data['name']) ? $input_data['name'] : null;



			$category = isset($input_data['category']) ? $input_data['category'] : null;



			if (empty($name)) {



				http_response_code(401);



				echo goResult(false, 'Name is required');



				return;

			}



			if (empty($category)) {



				http_response_code(401);



				echo goResult(false, 'Category is required');



				return;

			}



			$penjualanoption->name 			= $name;







			$penjualanoption->id_category 	= $category;















			if ($penjualanoption->save()) {







				echo goResult(true, 'Tag penjualan success for update');







				return;

			} else {



				http_response_code(401);



				echo goResult(false, 'Tag penjualan not success for update');







				return;

			}

		} else {



			http_response_code(404);



			echo goResult(false, 'Method tidak PUT');

		}

	}







	//API Getdata To Tambah Master Tag Penjualan



	public function master_tag_penjualan_getdata_tambah_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['optioncategory'] 	= PenjualanoptioncategoryModel::where('status', '1')->orderBy('name', 'asc')->get();



		echo goResult(true, $data);

	}







	//API Get Customer Master Customer



	public function master_customer_customer_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}







		$customerFilter 			= $this->input->get('customerfilter');







		if (!$customerFilter) {







			$valuecustomer 			= 'all';

		} else {







			$valuecustomer 			= $customerFilter;

		}







		if ($valuecustomer != 'all') {







			$totalcustomer 			= CustomernewModel::where('id', $valuecustomer)->where('status', 'customer')->where('status_deleted', 0)->orderBy('status_vip', 'desc')->orderBy('nama_perusahaan', 'asc')->get();







			$customer 				= CustomernewModel::take(20)->skip($page * 20)->where('id', $valuecustomer)->where('status', 'customer')->where('status_deleted', 0)->orderBy('status_vip', 'desc')->orderBy('nama_perusahaan', 'asc')->get();

		} else {







			$totalcustomer 			= CustomernewModel::where('status', 'customer')->where('status_deleted', 0)->orderBy('status_vip', 'desc')->orderBy('nama_perusahaan', 'asc')->get();







			$customer 				= CustomernewModel::take(20)->skip($page * 20)->where('status', 'customer')->where('status_deleted', 0)->orderBy('status_vip', 'desc')->orderBy('nama_perusahaan', 'asc')->get();

		}







		$paginate					= new Myweb_pagination;











		// $total						= count($totalcustomer);



		$data['customer'] 			= $customer;



		//  $data['allcustomer'] 		= CustomernewModel::where('status', 'customer')->where('status_deleted', 0)->orderBy('nama_perusahaan', 'asc')->get();



		$data['numberpage'] 		= $page * 20;







		$data['customerFilter'] 	= $valuecustomer;











		$data['numberpage'] 		= $page * 20;







		$data['customerFilter'] 	= $valuecustomer;



		echo goResult(true, $data);



		return;

	}







	//API Get Formuliredit Customer



	public function master_customer_customer_formuliredit_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		$customer 			= CustomernewModel::find($id);



		if (empty($customer)) {







			$this->response([



				'success' => false,



				'message' => 'Customer not found'



			], 404);



			return;

		}



		$data = array(



			'msg' => true,



			'data' => $customer



		);



		echo goResult(true, $data);

	}







	//API Getdata To Customer Master Customer



	public function master_customer_customer_getdata_tambah_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['kota'] 				= KotaModel::orderBy('name', 'asc')->get();



		echo goResult(true, $data);

	}







	//API Tambah Customer



	public function master_customer_customer_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$customer 			= new CustomernewModel;















		$rules 		= [







			'required' 	=> [







				['name'], ['email'], ['perusahaan'], ['alamat'], ['kota'], ['telephone'], ['whatsapp']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}







		if (substr($this->input->post('telephone'), 0, 1) == '0') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 1) == '0') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		if (substr($this->input->post('telephone'), 0, 3) == '+62') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 3) == '+62') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		$kota 				= KotaModel::find($this->input->post('kota'));







		if (!$kota) {







			echo goResult(false, 'Maaf, kota tidak ada');







			return;

		}







		$customerOld 			= CustomernewModel::desc()->first();







		if (!$customerOld) {







			$isToday 			= explode('-', date('Y-m-d'));







			$isYear 			= $isToday[0];







			$year 				= substr($isYear, -2);







			$month 				= $isToday[1];







			$day 				= $isToday[2];







			$newcode 			= 'C' . $year . '' . $month . '' . $day . '01';

		} else {







			$today 		= explode(' ', $customerOld->created_at);







			if ($today[0] == date('Y-m-d')) {







				$alphabet 		= (int) str_replace('C', '', $customerOld->code);







				$code 			= $alphabet + 1;







				$newcode 		= 'C' . $code;

			} else {







				$isToday 		= explode('-', date('Y-m-d'));







				$isYear 		= $isToday[0];







				$year 			= substr($isYear, -2);







				$month 			= $isToday[1];







				$day 			= $isToday[2];







				$newcode 		= 'C' . $year . '' . $month . '' . $day . '01';

			}

		}



		$customer->code 					= $newcode;







		$customer->name						= ucwords($this->input->post('name'));







		$customer->email					= $this->input->post('email');







		$customer->status_sheets 			= 0;







		$customer->active 					= 1;







		$customer->password 				= DefuseLib::encrypt(12345678);







		$customer->status 					= 'customer';







		$customer->status_regis 			= 'regclient';







		$customer->nama_perusahaan			= $this->input->post('perusahaan');







		$customer->alamat_penagihan_pengiriman 		= 'N';







		$customer->alamat_pengiriman 		= $this->input->post('alamat');







		$customer->alamat_penagihan 		= $this->input->post('alamat');







		$customer->id_kota 					= $kota->id;







		$customer->kota_perusahaan 			= $kota->name;















		$customer->country_code 			= 'id';







		$customer->tlp_whatsapp 			= $this->input->post('tlp_whatsapp');







		$customer->tlp_perusahaan 			= $this->input->post('telephone');







		$customer->whatsapp 				= $this->input->post('whatsapp');















		$maincustomer 		= array();







		$maincustomer[] 	= array(



			//profil







			'code' 			=> $newcode,







			'name' 			=> $this->input->post('perusahaan'),







			'ownname' 		=> ucwords($this->input->post('name')),







			'coordinate' 	=> '',















			//alamat







			'address' 		=> $this->input->post('alamat'),







			'businessname' 	=> $this->input->post('perusahaan'),







			'city_code' 	=> $kota->code,















			//kontak







			'namecont' 		=> ucwords($this->input->post('name')),







			'hp' 			=> '0' . $this->input->post('telephone'),







			'email' 		=> $this->input->post('email'),















			'pricelvl_id'	=> '1',







			'taxinconsale' 	=> false,







			'taxedonsale' 	=> false







		);















		$dataInsert 		= array(







			"bparray" 		=> $maincustomer







		);















		$curl 	= curl_init();







		curl_setopt_array($curl, array(







			CURLOPT_URL => "https://app.beecloud.id/api/v1/bpclient",







			CURLOPT_RETURNTRANSFER => true,







			CURLOPT_ENCODING => "",







			CURLOPT_MAXREDIRS => 10,







			CURLOPT_TIMEOUT => 0,







			CURLOPT_FOLLOWLOCATION => true,







			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,







			CURLOPT_CUSTOMREQUEST => "POST",







			CURLOPT_POSTFIELDS => json_encode($dataInsert),







			CURLOPT_HTTPHEADER => array(







				$this->ContentType,







				$this->AuthorizationPT







			),







		));







		$response 	= curl_exec($curl);







		curl_close($curl);







		$status 	= json_decode($response);















		$curlUD = curl_init();







		curl_setopt_array($curlUD, array(







			CURLOPT_URL => "https://app.beecloud.id/api/v1/bpclient",







			CURLOPT_RETURNTRANSFER => true,







			CURLOPT_ENCODING => "",







			CURLOPT_MAXREDIRS => 10,







			CURLOPT_TIMEOUT => 0,







			CURLOPT_FOLLOWLOCATION => true,







			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,







			CURLOPT_CUSTOMREQUEST => "POST",







			CURLOPT_POSTFIELDS => json_encode($dataInsert),







			CURLOPT_HTTPHEADER => array(







				$this->ContentType,







				$this->AuthorizationUD







			),







		));







		$responseUD = curl_exec($curlUD);







		curl_close($curlUD);







		$statusUD 	= json_decode($responseUD);















		if ($status->status == true && $statusUD->status == true) {







			$customer->save();















			echo goResult(true, 'Registrasi berhasil');







			return;

		} else {







			echo goResult(false, $response . ' - ' . $responseUD);







			return;

		}

	}







	//API Edit Customer



	public function master_customer_customer_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$rules 		= [







			'required' 	=> [







				['name'], ['email'], ['perusahaan'], ['alamat'], ['kota'], ['telephone'], ['whatsapp']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}











		$id = $this->input->post('id');



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Customer tidak ada');







			return;

		}















		if (substr($this->input->post('telephone'), 0, 1) == '0') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 1) == '0') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		if (substr($this->input->post('telephone'), 0, 3) == '+62') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 3) == '+62') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		$kota 				= KotaModel::find($this->input->post('kota'));







		if (!$kota) {







			echo goResult(false, 'Maaf, kota tidak ada');







			return;

		}















		$customer->name						= ucwords($this->input->post('name'));







		$customer->email					= $this->input->post('email');







		$customer->nama_perusahaan			= $this->input->post('perusahaan');







		$customer->alamat_pengiriman 		= $this->input->post('alamat');







		$customer->alamat_penagihan 		= $this->input->post('alamat');







		$customer->id_kota 					= $kota->id;







		$customer->kota_perusahaan 			= $kota->name;







		$customer->country_code 			= 'id';







		$customer->tlp_whatsapp 			= $this->input->post('tlp_whatsapp');







		$customer->tlp_perusahaan 			= $this->input->post('telephone');







		$customer->whatsapp 				= $this->input->post('whatsapp');















		if ($customer->save()) {



			$data = array(



				'msg' => 'Customer berhasil di edit',



				'customer' => $customer



			);



			unset($data['customer']['id']);



			echo goResult(true, $data);







			return;

		} else {







			echo goResult(false, 'Customer gagal di edit');







			return;

		}

	}







	//API Delete Customer



	public function master_customer_customer_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		if (empty($id)) {







			$this->response([



				'success' => false,



				'message' => 'Id null'



			], 401);



			return;

		}



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, Customer yang anda pilih tidak ada');







			return;

		}











		if ($customer->status_deleted != 1) {







			$customer->status_deleted 	= 1;







			$customer->save();















			CustomervipModel::where('id_customernew', $customer->id)->delete();















			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			echo goResult(true, 'Data anda sudah dihapus');







			return;

		}

	}







	//API Get Leads Master Customer Leads



	public function master_customer_leads_get()



	{



		// $data['allcustomer'] 		= CustomernewModel::where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();







		// $data['allteknisi'] 		= TeknisiModel::whereIn('status', ['super_admin', 'admin', 'kepala_toko'])->where('status_regis', '1')->orderBy('name', 'asc')->get();







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$customerFilter 			= $this->input->get('customerfilter');







		$teknisiFilter 				= $this->input->get('teknisifilter');







		$sumberFilter 				= $this->input->get('sumberfilter');















		if (!$customerFilter) {







			$valuecustomer 			= 'all';

		} else {







			$valuecustomer 			= $customerFilter;

		}















		if (!$teknisiFilter) {







			$valueteknisi 			= 'all';

		} else {







			$valueteknisi 			= $teknisiFilter;

		}















		if (!$sumberFilter) {







			$valuesumber 			= 'all';

		} else {







			$valuesumber 			= $sumberFilter;

		}















		if ($valuecustomer != 'all' && $valueteknisi != 'all' && $valuesumber != 'all') {















			$customer 				= CustomernewModel::where('id', $valuecustomer)->where('id_teknisi', $valueteknisi)->where('sumber', $valuesumber)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer != 'all' && $valueteknisi != 'all' && $valuesumber == 'all') {















			$customer 				= CustomernewModel::where('id', $valuecustomer)->where('id_teknisi', $valueteknisi)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer != 'all' && $valueteknisi == 'all' && $valuesumber != 'all') {















			$customer 				= CustomernewModel::where('id', $valuecustomer)->where('sumber', $valuesumber)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer == 'all' && $valueteknisi != 'all' && $valuesumber != 'all') {















			$customer 				= CustomernewModel::where('id_teknisi', $valueteknisi)->where('sumber', $valuesumber)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer != 'all' && $valueteknisi == 'all' && $valuesumber == 'all') {















			$customer 				= CustomernewModel::where('id', $valuecustomer)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer == 'all' && $valueteknisi != 'all' && $valuesumber == 'all') {















			$customer 				= CustomernewModel::where('id_teknisi', $valueteknisi)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} elseif ($valuecustomer == 'all' && $valueteknisi == 'all' && $valuesumber != 'all') {















			$customer 				= CustomernewModel::where('sumber', $valuesumber)->where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		} else {















			$customer 				= CustomernewModel::where('sumber', '!=', 'website')->where('sumber', '!=', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->orderBy('tgl_hubungi', 'desc')->get();

		}















		$idCustomer 				= array();







		foreach ($customer as $key => $value) {







			$idCustomer[] 			= $value->id;

		}















		$paginate					= new Myweb_pagination;















		$total						= count($customer);















		$data['numberpage'] 		= $page * 20;











		$id_teknisi = array();



		$data['customer'] 			= CustomernewModel::take(20)->skip($page * 20)->whereIn('id', $idCustomer)->orderBy('tgl_hubungi', 'desc')->get();



		$id_cusnew = array();



		$combinedData = [];



		foreach ($data['customer'] as $key => $value) {



			$id_cusnew[] = $value->id;







			$data['customer'][$key]->teknisi_name = $value->teknisi->name;







			$data['tags'] = CustomertagModel::whereIn('id_customernew', $id_cusnew)->get();

		}







		$data['customerFilter'] 	= $valuecustomer;







		$data['teknisiFilter'] 		= $valueteknisi;







		$data['sumberFilter'] 		= $valuesumber;







		echo goResult(true, $data);

	}







	//API Get Formulir edit Master Customer Leads



	public function master_customer_leads_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= CustomernewModel::find($id);



		if ($customer) {







			$data['customer'] 	= $customer;







			$data['tags'] 		= CustomertagModel::where('id_customernew', $customer->id)->get();



			echo goResult(true, $data);



			return;

		} else {



			$data = array(



				'msg' => 'Data customer not found',



			);



			http_response_code(404);



			echo goResult(false, $data);



			return;

		}

	}







	//API Get data To tambah Master Customer Leads



	public function master_customer_leads_getdata_tambah_get()



	{



		// dd('a');



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['adminsales'] = TeknisiModel::where('status_regis', '1')->where('status_sales', '1')->asc()->get();



		echo goResult(true, $data);

	}







	//API tambah Master Customer Leads



	public function master_customer_leads_tambah_post()



	{







		// dd('a');



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		//MITRA BISNIS







		$data['mitraBisnis'] 	= ApiBee::getMitrabisnisPT();







		$data['mitraBisnisUD'] 	= ApiBee::getMitrabisnisUD();















		//ITEM







		$data['masterItem'] 	= ApiBee::getMasterItemPT();







		$data['masterItemUD'] 	= ApiBee::getMasterItemUD();















		//HARGA ITEM







		$data['hargaItem'] 		= ApiBee::getHargaItemPT();







		$data['hargaItemUD'] 	= ApiBee::getHargaItemUD();















		//GUDANG







		$data['gudang'] 		= ApiBee::getGudangPT();







		$data['gudangUD'] 		= ApiBee::getGudangUD();















		$rules 		= [







			'required' 	=> [







				['database'], ['tgl_penawaran'], ['cabang'], ['mitrabisnis'], ['gudang'], ['sales']







			]







		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$salesid 		= TeknisiModel::where('id_bee', $this->input->post('sales'))->where('status_regis', '1')->where('status_sales', '1')->first();







		if (!$salesid) {







			echo goResult(false, 'Sales not found');







			return;

		}















		$id_teknisi 	= $salesid->id;















		$item 			= $this->input->post('item');







		$price 			= $this->input->post('price');







		$qty 			= $this->input->post('qty');







		$name 			= $this->input->post('name');







		$code 			= $this->input->post('code');







		$gudang 		= $this->input->post('gudang');







		$unit 			= $this->input->post('purcunit');







		$disc 			= $this->input->post('disc');







		$subtotal 		= $this->input->post('subtotal');







		$ppn 			= $this->input->post('ppn_item');















		$mitra 			= explode('-', $this->input->post('mitrabisnis'));







		$idmitra 		= $mitra[0];







		$codemitra 		= $mitra[1];







		$namemitra 		= $mitra[2];







		$idmitraFinal 	= array();







		$codemitraFinal	= array();







		$namemitraFinal	= array();















		$idgudangFinal 	= '';















		if ($this->input->post('database') == 'PT') {















			foreach ($data['mitraBisnis']['data'] as $key => $value) {







				if ($codemitra == $value->code) {







					$idmitraFinal[] 	= $value->id;







					$codemitraFinal[] 	= $value->code;







					$namemitraFinal[] 	= $value->name;

				}

			}















			foreach ($data['gudang']['data'] as $key => $value) {







				if ($gudang == $value->id) {







					$idgudangFinal 	= $idgudangFinal . '' . $value->id;

				}

			}















			$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'PT')->desc()->first();







			if (!$codeorderpenjualan) {







				$isToday 			= explode('-', date('Y-m-d'));







				$isYear 			= $isToday[0];







				$year 				= substr($isYear, -2);







				$month 				= $isToday[1];







				$day 				= $isToday[2];







				$newcodeorder 		= 'OPT-' . $year . '' . $month . '-001';

			} else {















				$today 				= explode(' ', $codeorderpenjualan->created_at);







				$dateToday 			= substr($today[0], 0, -3);







				$allpenjualan 		= PenawaranbeeModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();















				if ($dateToday == date('Y-m')) {















					$year 					= substr(date('Y'), -2);







					$newcode 				= count($allpenjualan) + 1;















					if ($newcode > 0 && $newcode <= 9) {







						$newSelectioncode 	= 'OPT-' . $year . '' . date('m') . '-00' . $newcode;

					} elseif ($newcode > 9 && $newcode <= 99) {







						$newSelectioncode 	= 'OPT-' . $year . '' . date('m') . '-0' . $newcode;

					} else {







						$newSelectioncode 	= 'OPT-' . $year . '' . date('m') . '-' . $newcode;

					}















					$lastSelection 			= PenawaranbeeModel::where('no_transaksi', $newSelectioncode)->get();







					if (count($lastSelection) > 0) {















						$newcode2 			= $newcode + 1;







						if ($newcode2 > 0 && $newcode2 <= 9) {







							$newcodeorder 	= 'OPT-' . $year . '' . date('m') . '-00' . $newcode2;

						} elseif ($newcode2 > 9 && $newcode2 <= 99) {







							$newcodeorder 	= 'OPT-' . $year . '' . date('m') . '-0' . $newcode2;

						} else {







							$newcodeorder 	= 'OPT-' . $year . '' . date('m') . '-' . $newcode2;

						}

					} else {







						$newcodeorder 		= $newSelectioncode;

					}

				} else {







					$isToday 			= explode('-', date('Y-m-d'));







					$isYear 			= $isToday[0];







					$year 				= substr($isYear, -2);







					$month 				= $isToday[1];







					$day 				= $isToday[2];







					$newcodeorder 		= 'OPT-' . $year . '' . $month . '-001';

				}

			}

		} else {















			foreach ($data['mitraBisnisUD']['data'] as $key => $value) {







				if ($codemitra == $value->code) {







					$idmitraFinal[] 	= $value->id;







					$codemitraFinal[] 	= $value->code;







					$namemitraFinal[] 	= $value->name;

				}

			}















			foreach ($data['gudangUD']['data'] as $key => $value) {







				if ($gudang == $value->id) {







					$idgudangFinal 	= $idgudangFinal . '' . $value->id;

				}

			}















			$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'UD')->desc()->first();







			if (!$codeorderpenjualan) {







				$isToday 			= explode('-', date('Y-m-d'));







				$isYear 			= $isToday[0];







				$year 				= substr($isYear, -2);







				$month 				= $isToday[1];







				$day 				= $isToday[2];







				$newcodeorder 		= 'OUD-' . $year . '' . $month . '-001';

			} else {















				$today 				= explode(' ', $codeorderpenjualan->created_at);







				$dateToday 			= substr($today[0], 0, -3);







				$allpenjualan 		= PenawaranbeeModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();















				if ($dateToday == date('Y-m')) {















					$year 					= substr(date('Y'), -2);







					$newcode 				= count($allpenjualan) + 1;















					if ($newcode > 0 && $newcode <= 9) {







						$newSelectioncode 	= 'OUD-' . $year . '' . date('m') . '-00' . $newcode;

					} elseif ($newcode > 9 && $newcode <= 99) {







						$newSelectioncode 	= 'OUD-' . $year . '' . date('m') . '-0' . $newcode;

					} else {







						$newSelectioncode 	= 'OUD-' . $year . '' . date('m') . '-' . $newcode;

					}















					$lastSelection 			= PenawaranbeeModel::where('no_transaksi', $newSelectioncode)->get();







					if (count($lastSelection) > 0) {















						$newcode2 			= $newcode + 1;







						if ($newcode2 > 0 && $newcode2 <= 9) {







							$newcodeorder 	= 'OUD-' . $year . '' . date('m') . '-00' . $newcode2;

						} elseif ($newcode2 > 9 && $newcode2 <= 99) {







							$newcodeorder 	= 'OUD-' . $year . '' . date('m') . '-0' . $newcode2;

						} else {







							$newcodeorder 	= 'OUD-' . $year . '' . date('m') . '-' . $newcode2;

						}

					} else {







						$newcodeorder 		= $newSelectioncode;

					}

				} else {







					$isToday 			= explode('-', date('Y-m-d'));







					$isYear 			= $isToday[0];







					$year 				= substr($isYear, -2);







					$month 				= $isToday[1];







					$day 				= $isToday[2];







					$newcodeorder 		= 'OUD-' . $year . '' . $month . '-001';

				}

			}

		}















		$itemnull =	0;







		for ($i = 0; $i < count($item); $i++) {







			if ($item[$i] == '') {







				$itemnull = $itemnull + 1;

			}

		}















		$qtynull =	0;







		for ($i = 0; $i < count($qty); $i++) {







			if ($qty[$i] == '') {







				$qtynull = $qtynull + 1;

			}















			if ($qty[$i] == 0) {







				$qtynull = $qtynull + 1;

			}

		}















		$pricenull =	0;







		for ($i = 0; $i < count($price); $i++) {







			if ($price[$i] == '') {







				$pricenull = $pricenull + 1;

			}















			if ($price[$i] == 0) {







				$pricenull = $pricenull + 1;

			}

		}















		if ($itemnull > 0) {







			echo goResult(false, 'Item is required');







			return;

		}















		if ($qtynull > 0) {







			echo goResult(false, 'Qty is required');







			return;

		}















		if ($pricenull > 0) {







			echo goResult(false, 'Price is required');







			return;

		}















		$totalprice 		= 0;







		$product 			= array();







		for ($i = 0; $i < count($item); $i++) {















			$itemFinal  	= explode('-', $item[$i]);







			$iditem 		= $itemFinal[0];







			$codeitem 		= $itemFinal[1];







			$iditemFinal 	= '';







			$codeitemFinal 	= '';







			$nameitemFinal 	= '';







			$unititemFinal 	= '';















			if ($this->input->post('database') == 'PT') {







				foreach ($data['masterItem']['data'] as $key => $value) {







					if ($codeitem == $value->code) {







						$iditemFinal 	= $iditemFinal . '' . $value->id;







						$codeitemFinal 	= $codeitemFinal . '' . $value->code;







						$nameitemFinal 	= $nameitemFinal . '' . $value->name1;







						$unititemFinal 	= $unititemFinal . '' . $value->purcunit;

					}

				}

			} else {







				foreach ($data['masterItemUD']['data'] as $key => $value) {







					if ($codeitem == $value->code) {







						$iditemFinal 	= $iditemFinal . '' . $value->id;







						$codeitemFinal 	= $codeitemFinal . '' . $value->code;







						$nameitemFinal 	= $nameitemFinal . '' . $value->name1;







						$unititemFinal 	= $unititemFinal . '' . $value->purcunit;

					}

				}

			}















			$totalprice 	= $totalprice + $subtotal[$i];















			if ($disc[$i] != '0') {







				$discpercent 		= round((100 * ($price[$i] - $disc[$i])) / $price[$i]);

			} else {







				$discpercent 		= 0;

			}















			if ($ppn[$i] == '0') {







				$product[] 			= array(







					"baseprice" 			=> $price[$i],







					"basesubtotal" 			=> $price[$i] * $qty[$i],







					"conv" 					=> "1",







					"discamt" 				=> $disc[$i],







					"discexp" 				=> $discpercent,







					"dno" 					=> $i + 1,







					"item_id" 				=> $iditemFinal,







					"itemname" 				=> $nameitemFinal,







					"listprice" 			=> $price[$i],







					"qty" 					=> $qty[$i],







					"subtotal" 				=> $subtotal[$i],







					"taxableamt" 			=> "0",







					"taxamt" 				=> "0",







					"totaldiscamt" 			=> "0",







					"basetotaltaxamt" 		=> "0",







					"basefistotaltaxamt" 	=> "0",







					"calcmtd2" 				=> '',







					"calcmtd3" 				=> '',







					"calcmtd4" 				=> '',







					"dnote" 				=> '',







					"unit" 					=> $unititemFinal,







					"wh_id" 				=> $idgudangFinal







				);

			} else {







				$product[] 			= array(







					"baseprice" 			=> $price[$i],







					"basesubtotal" 			=> $price[$i] * $qty[$i],







					"conv" 					=> "1",







					"discamt" 				=> $disc[$i],







					"discexp" 				=> $discpercent,







					"dno" 					=> $i + 1,







					"item_id" 				=> $iditemFinal,







					"itemname" 				=> $nameitemFinal,







					"listprice" 			=> $price[$i],







					"qty" 					=> $qty[$i],







					"subtotal" 				=> $subtotal[$i],







					"tax_code" 				=> "PPN",







					"taxableamt" 			=> "0",







					"taxamt" 				=> "0",







					"totaldiscamt" 			=> "0",







					"basetotaltaxamt" 		=> "0",







					"basefistotaltaxamt" 	=> "0",







					"calcmtd2" 				=> '',







					"calcmtd3" 				=> '',







					"calcmtd4" 				=> '',







					"dnote" 				=> '',







					"unit" 					=> $unititemFinal,







					"wh_id" 				=> $idgudangFinal







				);

			}

		}















		if ($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '1') {







			$statusppn 	= '1';







			$includeppn = '1';







			$taxed 		= true;







			$taxinc 	= true;

		} elseif ($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '0') {







			$statusppn 	= '1';







			$includeppn = '0';







			$taxed 		= true;







			$taxinc 	= false;

		} elseif ($this->input->post('ppn') == '0' && $this->input->post('includeppn') == '1') {







			$statusppn 	= '1';







			$includeppn = '1';







			$taxed 		= true;







			$taxinc 	= true;

		} else {







			$statusppn 	= '0';







			$includeppn = '0';







			$taxed 		= false;







			$taxinc 	= false;

		}















		$mainproduct 	= array();







		$mainproduct[] 	= array(







			"bp_id" 	=> $idmitraFinal[0],







			"crc_id" 	=> "1",







			"sods" 		=> $product,







			"srep_id" 	=> $this->input->post('sales'),







			"branch_id" => $this->input->post('cabang'),







			"subtotal" 	=> $this->input->post('td_subtotal'),







			"taxamt" 	=> $this->input->post('td_ppn'),







			"taxed" 	=> $taxed,







			"taxinc" 	=> $taxinc,







			"total" 	=> $this->input->post('td_total'),







			"trxdate" 	=> $this->input->post('tgl_penawaran'),







			"trxno"		=> $newcodeorder







		);















		$dataInsert 		= array(







			"soarray" 		=> $mainproduct







		);















		if ($this->input->post('database') == 'PT') {







			$newpenawaran 		= ApiBee::postPenawaranPT($dataInsert);

		} else {







			$newpenawaran 		= ApiBee::postPenawaranUD($dataInsert);

		}















		if ($newpenawaran['status'] != 200) {







			echo goResult(false, $newpenawaran['msg']);







			return;

		}















		$penawaranbee 						= new PenawaranbeeModel;







		$penawaranbee->tgl_transaksi 		= $this->input->post('tgl_penawaran');







		$penawaranbee->name_db 				= $this->input->post('database');







		$penawaranbee->no_transaksi 		= $newcodeorder;







		$penawaranbee->id_sales 			= $this->input->post('sales');







		$penawaranbee->id_teknisi 			= $id_teknisi;







		$penawaranbee->id_cabang 			= $this->input->post('cabang');







		$penawaranbee->id_mitrabisnis 		= $idmitraFinal[0];







		$penawaranbee->code_mitrabisnis 	= $codemitraFinal[0];







		$penawaranbee->ppn 					= $this->input->post('td_ppn');







		$penawaranbee->status_ppn 			= $statusppn;







		$penawaranbee->status_includeppn 	= $includeppn;







		$penawaranbee->subtotal 			= $this->input->post('td_total');







		$penawaranbee->keterangan 			= $this->input->post('keterangan');







		$penawaranbee->id_statusorder 		= $this->input->post('status_order');















		if ($penawaranbee->save()) {







			for ($i = 0; $i < count($item); $i++) {















				$itemFinal  	= explode('-', $item[$i]);







				$iditem 		= $itemFinal[0];







				$codeitem 		= $itemFinal[1];







				$iditemFinal 	= '';







				$codeitemFinal 	= '';







				$nameitemFinal 	= '';















				if ($this->input->post('database') == 'PT') {







					foreach ($data['masterItem']['data'] as $key => $value) {







						if ($codeitem == $value->code) {







							$iditemFinal 	= $iditemFinal . '' . $value->id;







							$codeitemFinal 	= $codeitemFinal . '' . $value->code;







							$nameitemFinal 	= $nameitemFinal . '' . $value->name1;

						}

					}

				} else {







					foreach ($data['masterItemUD']['data'] as $key => $value) {







						if ($codeitem == $value->code) {







							$iditemFinal 	= $iditemFinal . '' . $value->id;







							$codeitemFinal 	= $codeitemFinal . '' . $value->code;







							$nameitemFinal 	= $nameitemFinal . '' . $value->name1;

						}

					}

				}















				$penawaranbeedetail 					= new PenawaranbeedetailModel;







				$penawaranbeedetail->id_penawaranbee 	= $penawaranbee->id;







				$penawaranbeedetail->id_gudang 			= $gudang;







				$penawaranbeedetail->id_item 			= $iditemFinal;







				$penawaranbeedetail->code_item 			= $codeitemFinal;







				$penawaranbeedetail->name_item 			= $nameitemFinal;







				$penawaranbeedetail->price 				= $price[$i];







				$penawaranbeedetail->qty 				= $qty[$i];







				$penawaranbeedetail->disc 				= $disc[$i];







				$penawaranbeedetail->subtotal 			= $subtotal[$i];







				$penawaranbeedetail->ppn 				= 0;







				$penawaranbeedetail->status_ppn 		= $ppn[$i];







				$penawaranbeedetail->save();

			}







			echo goResult(true, 'Order penjualan berhasil dibuat');







			return;

		}

	}







	//API Edit Mster Customer Leads



	public function master_customer_leads_edit_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->post('id');



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, "Leads not found");

		}















		$rules 		= [







			'required' 	=> [







				['name'], ['whatsapp'], ['sumber'], ['status']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		if (substr($this->input->post('telephone'), 0, 1) == '0') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 1) == '0') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		if (substr($this->input->post('telephone'), 0, 3) == '+62') {







			echo goResult(false, "Nomor telephone salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 3) == '+62') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		$customer->name					= ucwords($this->input->post('name'));







		$customer->email				= $this->input->post('email');







		$customer->nama_perusahaan		= $this->input->post('perusahaan');







		$customer->whatsapp 			= $this->input->post('whatsapp');







		$customer->tlp_perusahaan 		= $this->input->post('telephone');







		$customer->keterangan 			= $this->input->post('keterangan');







		$customer->sumber 				= $this->input->post('sumber');







		$customer->status_leads 		= $this->input->post('status');







		$customer->nilai 				= $this->input->post('nilai');















		if ($customer->save()) {















			CustomertagModel::where('id_customernew', $customer->id)->delete();















			$counttags 					= count($this->input->post('tags'));







			for ($i = 0; $i < $counttags; $i++) {







				$tags 					= new CustomertagModel;







				$tags->id_customernew 	= $customer->id;







				$tags->name 			= $this->input->post('tags')[$i];







				$tags->save();

			}















			$data['auth'] 				= true;







			$data['msg'] 				= 'Leads berhasil di edit';















			echo toJson($data);







			return;

		} else {







			echo goResult(false, 'Leads gagal di edit');







			return;

		}

	}







	//API Delete Master Customer Leads



	public function master_customer_leads_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, leads tidak ada');







			return;

		}











		$customer->status_deleted 	= 1;







		$customer->save();















		CustomervipModel::where('id_customernew', $customer->id)->delete();







		echo goResult(true, 'Leads berhasil dihapus');

	}







	//API Get Master Qontak



	public function master_customer_qontak_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$idCustomer 				= array();







		$customer 					= QontakcontactModel::where('status', '1')->orderBy('created_at', 'desc')->get();







		foreach ($customer as $key => $value) {







			$idCustomer[] 			= $value->id;

		}















		$totalcustomerfix 			= QontakcontactModel::whereIn('id', $idCustomer)->where('name', 'like', '%' . $valuename . '%')->orWhere('perusahaan', 'like', '%' . $valuename . '%')->orderBy('created_at', 'desc')->get();







		$customerfix 				= QontakcontactModel::whereIn('id', $idCustomer)->take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->orWhere('perusahaan', 'like', '%' . $valuename . '%')->orderBy('created_at', 'desc')->get();















		$paginate					= new Myweb_pagination;















		$total						= count($totalcustomerfix);







		$data['customer'] 			= $customerfix;







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/customer_qontak/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);















		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);

	}







	//API Get Formulir edit Master Qontak



	public function master_customer_qontak_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= QontakcontactModel::find($id);



		$data['customer'] 	= $customer;



		if ($data['customer']) {



			echo goResult(true, $data);



			return;

		} else {



			echo goResult(false, 'Data tidak ditemukan');



			return;

		}

	}







	//API Delete Master Qontak



	public function master_customer_qontak_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= QontakcontactModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer qontak tidak ada');







			return;

		}















		$customer->status 	= '0';







		$customer->save();















		echo goResult(true, 'Data anda berhasil dihapus');







		return;

	}







	//API Edit Master Qontak



	public function master_customer_qontak_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->post('id');



		$customer 			= QontakcontactModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer qontak tidak ada');







			return;

		}















		$rules 		= [







			'required' 	=> [







				['name'], ['perusahaan'], ['telephone']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$customer->name 		= $this->input->post('name');







		$customer->perusahaan 	= $this->input->post('perusahaan');







		$customer->telephone 	= $this->input->post('telephone');















		if ($customer->save()) {







			echo goResult(true, 'Customer qontak berhasil di update');

		} else {







			echo goResult(false, 'Customer qontak gagal di update');

		}















		return;

	}







	//API Get Master Website



	public function master_customer_website_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$customer 					= CustomernewModel::where('name', 'like', '%' . $valuename . '%')->where('sumber', 'website')->where('status', 'leads')->where('status_deleted', 0)->desc()->get();















		$paginate					= new Myweb_pagination;















		$total						= count($customer);







		$data['customer'] 			= CustomernewModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('sumber', 'website')->where('status', 'leads')->where('status_deleted', 0)->desc()->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/customer_website/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);

	}







	//API Get Formulir edit Master Website



	public function master_customer_website_formuliredit_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= CustomernewModel::find($id);



		if ($customer) {







			$data['customer'] 	= $customer;



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Data not found');



			return;

		}

	}







	//API Edit Master Website



	public function master_customer_website_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->post('id');



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer website tidak ada');







			return;

		}















		$rules 		= [







			'required' 	=> [







				['name'], ['email'], ['telephone'], ['keterangan']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$customer->name 			= $this->input->post('name');







		$customer->email 			= $this->input->post('email');







		$customer->whatsapp 		= $this->input->post('telephone');







		$customer->tlp_perusahaan 	= $this->input->post('telephone');







		$customer->keterangan 		= $this->input->post('keterangan');















		if ($customer->save()) {







			echo goResult(true, 'Customer website berhasil di update');



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Customer website gagal di update');



			return;

		}

	}







	//API Delete Master Website



	public function master_customer_website_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 					= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer website tidak ada');







			return;

		}















		$customer->status_deleted 	= '1';







		$customer->save();















		echo goResult(true, 'Data Customer website berhasil dihapus');







		return;

	}







	//API Get Master Website



	public function master_customer_googleads_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}







		$nameFilter 				= $this->input->get('name');















		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}















		$customer 					= CustomernewModel::where('name', 'like', '%' . $valuename . '%')->where('sumber', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->desc()->get();















		$paginate					= new Myweb_pagination;















		$total						= count($customer);







		$data['customer'] 			= CustomernewModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('sumber', 'googleads')->where('status', 'leads')->where('status_deleted', 0)->desc()->get();







		$data['numberpage'] 		= $page * 20;







		//  $data['pagination'] 		= $paginate->paginate(base_url('teknisi/customer_googleads/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);

	}







	//API Get Formulir edit Master GoogleAds



	public function master_customer_googleads_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 			= CustomernewModel::find($id);



		if (!$customer) {



			http_response_code(404);



			echo goResult(false, 'Customer Googleads tidak ada');



			return;

		}



		$data['customer'] 	= $customer;



		echo goResult(true, $data);



		return;

	}







	//API Edit Master GoogleAds



	public function master_customer_googleads_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->post('id');



		$customer 			= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer google ads tidak ada');







			return;

		}















		$rules 		= [







			'required' 	=> [







				['name'], ['email'], ['telephone'], ['keterangan']







			]







		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		$customer->name 			= $this->input->post('name');







		$customer->email 			= $this->input->post('email');







		$customer->whatsapp 		= $this->input->post('telephone');







		$customer->tlp_perusahaan 	= $this->input->post('telephone');







		$customer->keterangan 		= $this->input->post('keterangan');















		if ($customer->save()) {







			echo goResult(true, 'Customer google ads berhasil di update');

		} else {







			echo goResult(false, 'Customer google ads gagal di update');

		}















		return;

	}







	//API Delete Master GoogleAds



	public function master_customer_googleads_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$customer 					= CustomernewModel::find($id);







		if (!$customer) {







			echo goResult(false, 'Maaf, customer google ads tidak ada');







			return;

		}















		$customer->status_deleted 	= '1';







		$customer->save();















		echo goResult(true, 'Data Customer google ads berhasil dihapus');







		return;

	}







	//API Get Master Supplier



	public function master_supplier_datasupplier_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');







		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}







		$supplier 					= SupplierModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();







		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;







		$total						= count($supplier);







		$data['supplier'] 			= SupplierModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->orderBy('name', 'asc')->get();







		$data['numberpage'] 		= $page * 20;







		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/supplier/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);











		$data['nameFilter'] 		= $valuename;



		echo goResult(true, $data);



		return;

	}







	public function master_suplier_datasuplier_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['name']















			]















		];











		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$supplier 				= new SupplierModel;







		$supplier->name 		= $this->input->post('name');















		$supplier->telp 		= $this->input->post('telp');















		$supplier->city 		= $this->input->post('kota');















		$supplier->address 		= $this->input->post('alamat');















		$supplier->company 		= $this->input->post('perusahaan');















		$supplier->status 		= '1';















		if ($supplier->save()) {







			echo goResult(true, 'Supplier berhasil di tambah');







			return;

		} else {







			echo goResult(false, 'Supplier gagal di tambah');







			return;

		}

	}







	public function master_suplier_datasuplier_formedit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['supplier'] 		= SupplierModel::find($id);



		if (!empty($data)) {



			echo goResult(true, $data['supplier']);



			return;

		}

	}







	public function master_suplier_datasuplier_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);



			return;

		}



		$id = $this->input->post('id');



		$supplier 				= SupplierModel::find($id);







		if (!$supplier) {











			echo goResult(false, "Supplier tidak ada");







			return;

		}







		$supplier->name 		= $this->input->post('name');







		$supplier->telp 		= $this->input->post('telp');







		$supplier->city 		= $this->input->post('kota');







		$supplier->address 		= $this->input->post('alamat');







		$supplier->company 		= $this->input->post('perusahaan');







		// $msg = array(



		// 	'msg' => 'Supplier berhasil di edit',



		// 	'data'=>$supplier,



		// );







		if ($supplier->save()) {







			echo goResult(true, 'Supplier berhasil di edit');







			return;

		} else {







			echo goResult(false, 'Supplier gagal di edit');







			return;

		}

	}







	public function master_suplier_datasuplier_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$supplier 				= SupplierModel::find($id);















		if (!$supplier) {















			echo goResult(false, 'Maaf, supplier tidak ada');















			return;

		}



		if ($supplier->status != 0) {







			$supplier->status 		= '0';







			$supplier->save();











			echo goResult(true, 'Data anda berhasil dihapus');











			return;

		} else {



			echo goResult(False, 'Data anda sudah dihapus');

		}

	}







	public function master_suplier_banksuplier_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');







		if (!$nameFilter) {







			$valuename 				= '';

		} else {







			$valuename 				= $nameFilter;

		}







		$supplierbank 				= SupplierbankModel::where('bank_name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;







		$total						= count($supplierbank);







		$data['supplierbank'] = SupplierbankModel::join('supplier', 'supplier_bank.id_supplier', '=', 'supplier.id')->join('matauang', 'supplier_bank.id_matauang', '=', 'matauang.id')



			->take(20)



			->skip($page * 20)



			->where('supplier_bank.bank_name', 'like', '%' . $valuename . '%')



			->where('supplier_bank.status', '1')



			->select('supplier_bank.bank_name', 'supplier_bank.beneficiary_name', 'supplier_bank.account_number', 'supplier.name as supplier_name', 'matauang.simbol as simbol', 'matauang.kode as kode')



			->get();











		$data['numberpage'] 		= $page * 20;







		$data['nameFilter'] 		= $valuename;



		echo goResult(true, $data);



		return;

	}







	public function master_suplier_banksuplier_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['supplierbank'] 	= SupplierbankModel::join('supplier', 'supplier_bank.id_supplier', '=', 'supplier.id')->join('matauang', 'supplier_bank.id_matauang', '=', 'matauang.id')



			// ->where('supplier_bank.bank_name', 'like', '%' . $valuename . '%')



			->where('supplier_bank.id', $id)



			->select('supplier.name as supplier_name', 'matauang.simbol as simbol', 'matauang.kode as kode', 'supplier_bank.bank_name', 'supplier_bank.beneficiary_address as bank_address', 'supplier_bank.swiftcode as swiftcode', 'supplier_bank.account_number', 'supplier_bank.beneficiary_name',)



			->get();







		if ($data['supplierbank'] = SupplierbankModel::find($id)) {







			echo goResult(true, $data);



			return;

		} else {







			$msg = array(



				'msg' => 'Supplierbank not found'



			);



			http_response_code(404);



			echo goResult(false, $msg);



			return;

		}

	}







	public function master_suplier_banksuplier_viewtambah_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['supplier'] 			= SupplierModel::where('status', '1')->orderBy('name', 'asc')->get();















		$data['matauang'] 			= MatauangModel::where('status', '1')->orderBy('kode', 'asc')->get();











		echo goResult(true, $data);

	}







	public function master_suplier_banksuplier_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['supplier'], ['matauang']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$supplierbank 						= new SupplierbankModel;















		$supplierbank->id_supplier 			= $this->input->post('supplier');















		$supplierbank->id_matauang 			= $this->input->post('matauang');















		$supplierbank->bank_name 			= $this->input->post('bank_name');















		$supplierbank->bank_address 		= $this->input->post('bank_address');















		$supplierbank->swiftcode 			= $this->input->post('swiftcode');















		$supplierbank->account_number 		= $this->input->post('account_number');















		$supplierbank->beneficiary_name 	= $this->input->post('beneficiary_name');















		$supplierbank->beneficiary_address 	= $this->input->post('beneficiary_address');















		$supplierbank->status 				= '1';











		if ($supplierbank->save()) {















			echo goResult(true, 'Bank supplier success for save');















			return;

		} else {















			echo goResult(false, 'Bank supplier cannot for save');















			return;

		}

	}







	public function master_suplier_banksuplier_edit_post()



	{











		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['supplier'], ['matauang']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}























		$id = $this->input->post('id');







		$supplierbank 				= SupplierbankModel::find($id);















		if (!$supplierbank) {















			echo goResult(false, "Bank supplier not found");















			return;

		}































		$supplierbank->id_supplier 			= $this->input->post('supplier');















		$supplierbank->id_matauang 			= $this->input->post('matauang');















		$supplierbank->bank_name 			= $this->input->post('bank_name');















		$supplierbank->bank_address 		= $this->input->post('bank_address');















		$supplierbank->swiftcode 			= $this->input->post('swiftcode');















		$supplierbank->account_number 		= $this->input->post('account_number');















		$supplierbank->beneficiary_name 	= $this->input->post('beneficiary_name');















		$supplierbank->beneficiary_address 	= $this->input->post('beneficiary_address');































		if ($supplierbank->save()) {















			echo goResult(true, 'Bank supplier success for update');















			return;

		} else {















			echo goResult(false, 'Bank supplier cannot for update');















			return;

		}

	}







	public function master_suplier_banksuplier_hapus_delete()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$supplierbank 				= SupplierbankModel::find($id);















		if (!$supplierbank) {











			http_response_code(404);



			echo goResult(false, 'Bank supplier not found');















			return;

		}



























		if ($supplierbank->status != 0) {







			$supplierbank->status 		= '0';















			$supplierbank->save();















			echo goResult(true, 'Data anda berhasil dihapus');















			return;

		} else {



			http_response_code(401);



			echo goResult(false, 'Data sudah dihapus');







			return;

		}

	}







	public function master_bankaccount_get()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 			= $this->input->get('name');







		if (!$nameFilter) {















			$valuename 			= '';

		} else {















			$valuename 			= $nameFilter;

		}







		$account 				= AccountModel::where('name', 'like', '%' . $valuename . '%')->orWhere('bank_name', 'like', '%' . $valuename . '%')->orWhere('bank_number', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();











		$page 					= $this->uri->segment(5);















		if (!is_numeric($page)) {















			$page 				= $this->input->get('page');

		}











		$paginate				= new Myweb_pagination;







		$total					= count($account);











		$data['account'] 		= AccountModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->orWhere('bank_name', 'like', '%' . $valuename . '%')->orWhere('bank_number', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 	= $page * 20;







		$data['nameFilter'] 	= $valuename;







		echo goResult(true, $data);



		return;

	}







	public function master_bankaccount_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['account'] 		= AccountModel::find($id);



		if (isset($data['account'])) {



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Data Bank Account Tidak Ada');



			return;

		}

	}







	public function master_bankaccount_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$account 				= AccountModel::find($id);















		if (!$account) {















			echo goResult(false, 'Maaf, bank account tidak ada');















			return;

		}



























		if ($account->status != 0) {







			$account->status 		= '0';











			$account->save();











			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			http_response_code(401);



			echo goResult(false, 'Data sudah dihapus');



			return;

		}

	}







	public function master_coa_akun_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$data['allcoa'] 			= CoaModel::where('status', '1')->asc()->get();











		$page 						= $this->uri->segment(5);















		if (!is_numeric($page)) {















			$page 					= $this->input->get('page');

		}











		$coaFilter 					= $this->input->get('customerfilter');















		if (!$coaFilter) {















			$valuecoa 				= 'all';

		} else {















			$valuecoa 				= $coaFilter;

		}































		if ($valuecoa != 'all') {















			$totalcoa 				= CoaModel::where('id', $valuecoa)->where('status', '1')->asc()->get();















			$coa 					= CoaModel::take(20)->skip($page * 20)->where('id', $valuecoa)->where('status', '1')->asc()->get();

		} else {















			$totalcoa 				= CoaModel::where('status', '1')->asc()->get();















			$coa 					= CoaModel::take(20)->skip($page * 20)->where('status', '1')->asc()->get();

		}































		$paginate					= new Myweb_pagination;































		$total						= count($totalcoa);















		$data['coa'] 				= $coa;















		$data['numberpage'] 		= $page * 20;











		// $data['pagination'] 		= $paginate->paginate(base_url('teknisi/coa/'.$this->data['teknisi']->username.'/page/'),6,20,$total,$page);







		$data['coaFilter'] 			= $valuecoa;







		echo goResult(true, $data);



		return;

	}







	public function master_coa_akun_formulir_edit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['coa'] = CoaModel::join('klasifikasi', 'coa.id_klasifikasi', '=', 'klasifikasi.id')



			->where('coa.id_klasifikasi', $id)



			->select('coa.code', 'coa.name', 'klasifikasi.name as klasifikasi_name')



			->first();











		if (isset($data['coa'])) {



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Coa Not Found');



			return;

		}

	}







	public function master_coa_akun_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['code'], ['name'], ['klasifikasi']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$coa 					= new CoaModel;















		$coa->id_klasifikasi 	= $this->input->post('klasifikasi');















		$coa->code 				= $this->input->post('code');















		$coa->name 				= $this->input->post('name');















		$coa->status 			= '1';































		if ($coa->save()) {















			echo goResult(true, 'COA berhasil di tambah');















			return;

		} else {















			echo goResult(false, 'COA gagal di tambah');















			return;

		}

	}







	public function master_coa_akun_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['code'], ['name'], ['klasifikasi']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$id = $this->input->post('id');







		$coa 					= CoaModel::find($id);















		if (!$coa) {







			http_response_code(404);



			echo goResult(false, "COA tidak ada");



			return;

		}











		$coa->id_klasifikasi 	= $this->input->post('klasifikasi');











		$coa->code 				= $this->input->post('code');











		$coa->name 				= $this->input->post('name');











		if ($coa->save()) {











			echo goResult(true, 'COA berhasil di edit');







			return;

		} else {











			http_response_code(401);



			echo goResult(false, 'COA gagal di edit');







			return;

		}

	}







	public function master_coa_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$coa 						= CoaModel::find($id);















		if (!$coa) {











			http_response_code(404);



			echo goResult(false, 'Maaf, coa tidak ada');







			return;

		}







		if ($coa->status != 0) {







			$coa->status 				= '0';







			$coa->save();







			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			http_response_code(401);



			echo goResult(false, 'Data sudah dihapus');







			return;

		}

	}







	public function master_bankaccount_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}











		$rules = [















			'required' 	=> [















				['name'], ['bank_name'], ['bank_number'], ['bank_account']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}































		$account 				= new AccountModel;















		$account->name 			= $this->input->post('name');















		$account->bank_name 	= $this->input->post('bank_name');















		$account->bank_number 	= $this->input->post('bank_number');















		$account->bank_account 	= $this->input->post('bank_account');











		$account->status 		= '1';











		if ($account->save()) {















			echo goResult(true, 'Bank Account berhasil di tambah');















			return;

		} else {















			echo goResult(false, 'Bank Account gagal di tambah');















			return;

		}

	}







	public function master_bankaccount_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['name'], ['bank_name'], ['bank_number'], ['bank_account']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$id = $this->input->post('id');







		$account 				= AccountModel::find($id);















		if (!$account) {











			http_response_code(404);



			echo goResult(false, "Bank Account tidak ada");















			return;

		}











		$account->name 			= $this->input->post('name');















		$account->bank_name 	= $this->input->post('bank_name');















		$account->bank_number 	= $this->input->post('bank_number');















		$account->bank_account 	= $this->input->post('bank_account');































		if ($account->save()) {















			echo goResult(true, 'Bank Account berhasil di edit');















			return;

		} else {















			echo goResult(false, 'Bank Account gagal di edit');















			return;

		}

	}







	public function master_klasifikasiaccount_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 				= $this->input->get('name');







		if (!$nameFilter) {







			$valuename 				= '';

		} else {



			$valuename 				= $nameFilter;

		}







		$klasifikasi 				= KlasifikasiModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$page 						= $this->uri->segment(5);















		if (!is_numeric($page)) {















			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;







		$total						= count($klasifikasi);







		$data['klasifikasi'] 		= KlasifikasiModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$data['numberpage'] 		= $page * 20;







		$data['nameFilter'] 		= $valuename;







		echo goResult(true, $data);



		return;

	}







	public function master_klasifikasiaccount_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['klasifikasi'] 			= KlasifikasiModel::find($id);



		if (!empty($data['klasifikasi'])) {



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Data not found');



			return;

		}

	}







	public function master_klasifikasiaccount_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}































		$klasifikasi 					= new KlasifikasiModel;















		$klasifikasi->name 				= $this->input->post('name');















		$klasifikasi->status 			= '1';































		if ($klasifikasi->save()) {















			echo goResult(true, 'Klasifikasi berhasil di tambah');















			return;

		} else {















			echo goResult(false, 'Klasifikasi gagal di tambah');















			return;

		}

	}







	public function master_klasifikasiaccount_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}











		$id = $this->input->post('id');







		$klasifikasi 					= KlasifikasiModel::find($id);















		if (!$klasifikasi) {











			http_response_code(404);



			echo goResult(false, "Klasifikasi tidak ada");















			return;

		}































		$klasifikasi->name 				= $this->input->post('name');































		if ($klasifikasi->save()) {















			echo goResult(true, 'Klasifikasi berhasil di edit');















			return;

		} else {















			echo goResult(false, 'Klasifikasi gagal di edit');















			return;

		}

	}







	public function master_klasifikasiaccount_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$klasifikasi 						= KlasifikasiModel::find($id);















		if (!$klasifikasi) {















			echo goResult(false, 'Maaf, klasifikasi tidak ada');















			return;

		}



























		if ($klasifikasi->status != 0) {







			$klasifikasi->status 				= '0';







			$klasifikasi->save();







			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			http_response_code(401);



			echo goResult(false, 'Data sudah dihapus');



			return;

		}

	}







	public function master_quotes_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$nameFilter 				= $this->input->get('name');







		if (!$nameFilter) {















			$valuename 				= '';

		} else {















			$valuename 				= $nameFilter;

		}







		$quotes 					= QuotesModel::where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();







		$page 						= $this->uri->segment(5);















		if (!is_numeric($page)) {















			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;











		$total						= count($quotes);







		$data['quotes'] 			= QuotesModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $valuename . '%')->where('status', '1')->asc()->get();











		$data['numberpage'] 		= $page * 20;







		$data['nameFilter'] 		= $valuename;



		echo goResult(true, $data);



		return;

	}







	public function master_quotes_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}































		$quotes 					= new QuotesModel;















		$quotes->name 				= $this->input->post('name');















		$quotes->status 			= '1';































		if ($quotes->save()) {















			echo goResult(true, 'Quotes berhasil di tambah');















			return;

		} else {















			echo goResult(false, 'Quotes gagal di tambah');















			return;

		}

	}







	public function master_quotes_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}







		$id = $this->input->post('id');



		$quotes 					= QuotesModel::find($id);















		if (!$quotes) {











			http_response_code(404);



			echo goResult(false, "Quotes tidak ada");















			return;

		}































		$quotes->name 				= $this->input->post('name');































		if ($quotes->save()) {















			echo goResult(true, 'Quotes berhasil di edit');















			return;

		} else {















			echo goResult(false, 'Quotes gagal di edit');















			return;

		}

	}







	public function master_quotes_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['quotes'] 				= QuotesModel::find($id);







		if (!empty($data['quotes'])) {



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Quotes not found');



			return;

		}

	}







	public function master_quotes_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$quotes 					= QuotesModel::find($id);















		if (!$quotes) {















			echo goResult(false, 'Maaf, quotes tidak ada');















			return;

		}











		if ($quotes->status != 0) {







			$quotes->status 			= '0';







			$quotes->save();











			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			http_response_code(400);



			echo goResult(false, 'Data sudah dihapus');







			return;

		}

	}







	public function master_landingpages_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$nameFilter 			= $this->input->get('name');







		if (!$nameFilter) {















			$valuename 			= '';

		} else {















			$valuename 			= $nameFilter;

		}











		if ($valuename == '') {















			$landingpage 		= LandingpageModel::where('status', '1')->asc()->get();

		} else {











			$product 			= BarangModel::where('name', 'like', '%' . $valuename . '%')->orWhere('new_kode', 'like', '%' . $valuename . '%')->orderBy('new_kode', 'asc')->get();











			$idProduct 			= array();















			foreach ($product as $key => $value) {















				$idProduct[] 	= $value->id;

			}











			$landingpage 		= LandingpageModel::whereIn('id_barang', $idProduct)->where('status', '1')->asc()->get();

		}











		$idLandingpage 			= array();















		foreach ($landingpage as $key => $value) {















			$idLandingpage[] 	= $value->id_barang;

		}











		$page 					= $this->uri->segment(5);







		if (!is_numeric($page)) {















			$page 				= $this->input->get('page');

		}











		$paginate				= new Myweb_pagination;











		$total					= count($idLandingpage);











		$data['landingpage'] 	= LandingpageModel::whereIn('id_barang', $idLandingpage)



			->join('barang', 'landingpage.id_barang', '=', 'barang.id')



			->select('barang.new_kode', 'barang.name', 'landingpage.id', 'landingpage.price', 'landingpage.price_coret', 'landingpage.link')



			->take(20)->skip($page * 20)->get();







		$data['numberpage'] 	= $page * 20;











		$data['nameFilter'] 	= $valuename;







		echo goResult(true, $data);







		return;

	}







	public function master_landingpages_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['landingpage'] 		= LandingpageModel::join('barang', 'landingpage.id_barang', '=', 'barang.id')



			->where('landingpage.id', $id)



			->select('barang.name', 'barang.new_kode', 'landingpage.id', 'landingpage.price', 'landingpage.price_coret', 'landingpage.link')->get();



		if ($data['landingpage']->isEmpty()) {



			http_response_code(404);



			echo goResult(false, 'Data not found');



			return;

		} else {



			echo goResult(true, $data);



			return;

		}

	}







	public function master_landingpages_viewtambah_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$query = $this->db->select('name,id,new_kode')



			->from('barang')



			->where('status_deleted', '0')



			->order_by('new_kode', 'asc')



			->get();







		$name_barang = $query->result_array();



		$data = array('data' => $name_barang);



		echo json_encode($data);



		return;

	}







	public function master_landingpages_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$rules = [















			'required' 	=> [















				['product'], ['price'], ['price_coret'], ['link']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {















			echo goResult(false, $validate->msg);















			return;

		}































		$landingpage 				= new LandingpageModel;















		$landingpage->id_barang 	= $this->input->post('product');















		$landingpage->price 		= $this->input->post('price');















		$landingpage->price_coret 	= $this->input->post('price_coret');















		$landingpage->link 			= $this->input->post('link');















		$landingpage->status 		= '1';































		if ($landingpage->save()) {















			echo goResult(true, 'Landing page berhasil di tambah');















			return;

		} else {















			echo goResult(false, 'Landing page gagal di tambah');















			return;

		}

	}







	public function master_landingpages_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['product'], ['price'], ['price_coret'], ['link']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}







		$id = $this->input->post('id');



		$landingpage 				= LandingpageModel::find($id);















		if (!$landingpage) {











			http_response_code(404);



			echo goResult(false, "Landing page tidak ada");















			return;

		}











		$landingpage->id_barang 	= $this->input->post('product');







		$landingpage->price 		= $this->input->post('price');







		$landingpage->price_coret 	= $this->input->post('price_coret');















		$landingpage->link 			= $this->input->post('link');











		if ($landingpage->save()) {















			echo goResult(true, 'Landing page berhasil di edit');















			return;

		} else {







			http_response_code(400);



			echo goResult(false, 'Landing page gagal di edit');







			return;

		}

	}







	public function master_landingpages_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');



		$landingpage 				= LandingpageModel::find($id);







		if (!$landingpage) {







			http_response_code(404);



			echo goResult(false, 'Maaf, landing page tidak ada');



			return;

		}







		if ($landingpage->status != 0) {







			$landingpage->status 		= '0';







			$landingpage->save();







			echo goResult(true, 'Data anda berhasil dihapus');







			return;

		} else {



			http_response_code(400);



			echo goResult(false, 'Data sudah dihapus');



			return;

		}

	}







	public function master_flyer_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$page 						= $this->uri->segment(5);















		if (!is_numeric($page)) {















			$page 					= $this->input->get('page');

		}











		$nameFilter 				= $this->input->get('namefilter');















		$total 						= FlyerModel::where('name', 'like', '%' . $nameFilter . '%')->orderBy('name', 'asc')->get();















		$flyer 						= FlyerModel::take(20)->skip($page * 20)->where('name', 'like', '%' . $nameFilter . '%')->orderBy('name', 'asc')->get();











		$paginate					= new Myweb_pagination;







		$total						= count($total);







		$data['flyer'] 				= $flyer;







		$data['numberpage'] 		= $page * 20;







		$data['nameFilter'] 		= $nameFilter;







		echo goResult(true, $data);



		return;

	}







	public function master_flyer_formuliredit_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$data['flyer'] 			= FlyerModel::find($id);







		if (empty($data['flyer'])) {



			http_response_code(404);



			echo goResult(false, 'Flyer not found');



			return;

		} else {







			echo goResult(true, $data);



			return;

		}

	}







	public function master_flyer_tambah_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {











			http_response_code(400);



			echo goResult(false, $validate->msg);















			return;

		}































		$flyer 				= new FlyerModel;















		$flyer->name 		= $this->input->post('name');































		if (!empty($_FILES['image']['name']) && $this->isImage('image') == true) {















			$filename 		= 'flyer-' . seo($this->input->post('name'));















			$upload 		= $this->upload('images/flayer', 'image', $filename);















			if ($upload['auth']	== false) {















				echo goResult(false, $upload['msg']);















				return;

			}































			$flyer->image 	= $upload['msg']['file_name'];

		}































		if ($flyer->save()) {































			$newname 		= $flyer->id . '.flyer-' . seo($this->input->post('name')) . '.jpg';































			move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public_html/images/flayer/' . $newname);















			remFile(__DIR__ . '/../../public_html/images/flayer/' . $flyer->image);































			$flyerId 		= FlyerModel::find($flyer->id);















			$flyerId->image = $newname;















			$flyerId->save();































			echo goResult(true, 'Flyer succes for save');















			return;

		} else {















			echo goResult(false, 'Flyer cannot for save');















			return;

		}

	}







	public function master_flyer_edit_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [















			'required' 	=> [















				['name']















			]















		];















		$validate 	= Validation::check($rules, 'post');















		if (!$validate->auth) {











			http_response_code(400);



			echo goResult(false, $validate->msg);







			return;

		}







		$id = $this->input->post('id');



		$flyer 				= FlyerModel::find($id);







		if (!$flyer) {











			http_response_code(404);



			echo goResult(false, "Flyer not found");















			return;

		}































		$flyer->name 		= $this->input->post('name');































		if (!empty($_FILES['image']['name']) && $this->isImage('image') == true) {































			$lastname 		= substr($flyer->image, 0, -4);















			$arrname 		= explode('.', $lastname);















			$newname 		= $arrname[0] . '.' . $arrname[1] . '.jpg';















			$filename 		= $arrname[1];































			if ($flyer->image !== "") {















				remFile(__DIR__ . '/../../public_html/images/flayer/' . $flyer->image);

			}































			$upload 		= $this->upload('images/flayer', 'image', $filename);















			if ($upload['auth']	== false) {















				echo goResult(false, $upload['msg']);















				return;

			}































			move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public_html/images/flayer/' . $newname);















			remFile(__DIR__ . '/../../public_html/images/flayer/' . $filename . '.jpg');

		}































		if ($flyer->save()) {















			echo goResult(true, 'Flyer success for update');















			return;

		} else {











			http_response_code(400);



			echo goResult(false, 'Flyer cannot for update');















			return;

		}

	}







	public function master_flyer_hapus_delete()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$flyer 						= FlyerModel::find($id);















		if (!$flyer) {











			http_response_code(404);



			echo goResult(false, 'Flyer not found');







			return;

		}







		if ($flyer->image !== "") {















			remFile(__DIR__ . '/../../public_html/images/flayer/' . $flyer->image);

		}











		FlyerModel::find($id)->delete();











		echo goResult(true, 'Flyer success for delete');















		return;

	}



	public function order_penjualan_tambah_post(){
			$teknisi_id = $this->session->userdata('teknisi_id');

			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



			if (!$teknisi_id) {

				$teknisi_id = $this->input->post('teknisi_id');

			}
			$data['mitraBisnis'] 	= ApiBee::getMitrabisnisPT();
			$data['mitraBisnisUD'] 	= ApiBee::getMitrabisnisUD();
			
			//ITEM
			$data['masterItem'] 	= ApiBee::getMasterItemPT();
			$data['masterItemUD'] 	= ApiBee::getMasterItemUD();
			
			//HARGA ITEM
			$data['hargaItem'] 		= ApiBee::getHargaItemPT();
			$data['hargaItemUD'] 	= ApiBee::getHargaItemUD();
			
			//GUDANG
			$data['gudang'] 		= ApiBee::getGudangPT();
			$data['gudangUD'] 		= ApiBee::getGudangUD();
			
			$rules 		= [
				'required' 	=> [
					['database'],['tgl_penawaran'],['cabang'],['gudang'],['sales']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			$salesid 		= TeknisiModel::where('id_bee', $this->input->post('sales'))->where('status_regis', '1')->where('status_sales', '1')->first();
			if(!$salesid){
				echo goResult(false, 'Sales not found');
				return;
			}

			$id_teknisi 	= $salesid->id;
			$item 			= $this->input->post('item');
			$price 			= $this->input->post('price');
			$qty 			= $this->input->post('qty');
			$name 			= $this->input->post('name');
			$code 			= $this->input->post('code');
			$gudang 		= $this->input->post('gudang');
			$unit 			= $this->input->post('purcunit');
			$disc 			= $this->input->post('disc');
			$subtotal 		= $this->input->post('subtotal');
			$ppn 			= $this->input->post('ppn_item');
		
			$idmitra 		= $this->input->post('id_mitrabisnis');
			$codemitra 		= $this->input->post('code_mitrabisnis');
			$namemitra 		= $this->input->post('name_mitrabisnis');
				// dd([
				//     'id_teknisi' => $id_teknisi,
				//     'item' => $item,
				//     'price' => $price,
				//     'qty' => $qty,
				//     'name' => $name,
				//     'code' => $code,
				//     'gudang' => $gudang,
				//     'unit' => $unit,
				//     'disc' => $disc,
				//     'subtotal' => $subtotal,
				//     'ppn' => $ppn,
				
				//     'idmitra' => $idmitra,
				//     'codemitra' => $codemitra,
				//     'namemitra' => $namemitra,
				// ]);

			$idmitraFinal 	= array();
			$codemitraFinal	= array();
			$namemitraFinal	= array();
			$idgudangFinal 	= '';
			// dd($this->input->post('database'));
			if($this->input->post('database') == 'PT'){
						
				foreach ($data['mitraBisnis']['data'] as $key => $value) {
					// dd($codemitra,$value->code);
					if($codemitra == $value->code){
						$idmitraFinal[] 	= $value->id;
						$codemitraFinal[] 	= $value->code;
						$namemitraFinal[] 	= $value->name;
						// dd($idmitraFinal,$codemitraFinal,$idgudangFinal);
					}
					// dd('tidak sama');
				}

				foreach ($data['gudang']['data'] as $key => $value) {
					if($gudang == $value->id){
						$idgudangFinal 	= $idgudangFinal.''.$value->id;
					}
				}
				// dd('a');
				$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'PT')->desc()->first();
				if(!$codeorderpenjualan){
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodeorder 		= 'OPT-'.$year.''.$month.'-001';
				}else{
					$today 				= explode(' ', $codeorderpenjualan->created_at);
					$dateToday 			= substr($today[0], 0, -3);
					$allpenjualan 		= PenawaranbeeModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
					if($dateToday == date('Y-m')){
						$year 					= substr(date('Y'), -2);
						$newcode 				= count($allpenjualan) + 1;

						if($newcode > 0 && $newcode <= 9){
							$newSelectioncode 	= 'OPT-'.$year.''.date('m').'-00'.$newcode;
						}elseif($newcode > 9 && $newcode <= 99){
							$newSelectioncode 	= 'OPT-'.$year.''.date('m').'-0'.$newcode;
						}else{
							$newSelectioncode 	= 'OPT-'.$year.''.date('m').'-'.$newcode;
						}

						$lastSelection 			= PenawaranbeeModel::where('no_transaksi', $newSelectioncode)->get();
						if(count($lastSelection) > 0){
							$newcode2 			= $newcode + 1;
							if($newcode2 > 0 && $newcode2 <= 9){
								$newcodeorder 	= 'OPT-'.$year.''.date('m').'-00'.$newcode2;
							}elseif($newcode2 > 9 && $newcode2 <= 99){
								$newcodeorder 	= 'OPT-'.$year.''.date('m').'-0'.$newcode2;
							}else{
								$newcodeorder 	= 'OPT-'.$year.''.date('m').'-'.$newcode2;
							}
						}else{
							$newcodeorder 		= $newSelectioncode;
						}
					}else{
						$isToday 			= explode('-', date('Y-m-d'));
						$isYear 			= $isToday[0];
						$year 				= substr($isYear, -2);
						$month 				= $isToday[1];
						$day 				= $isToday[2];
						$newcodeorder 		= 'OPT-'.$year.''.$month.'-001';
					}
				}
			}else{
				foreach ($data['mitraBisnisUD']['data'] as $key => $value) {
					if($codemitra == $value->code){
						$idmitraFinal[] 	= $value->id;
						$codemitraFinal[] 	= $value->code;
						$namemitraFinal[] 	= $value->name;
					}
					// dd($idmitraFinal);
				}

				foreach ($data['gudangUD']['data'] as $key => $value) {
					if($gudang == $value->id){
						$idgudangFinal 	= $idgudangFinal.''.$value->id;
					}
				}

				$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'UD')->desc()->first();
				if(!$codeorderpenjualan){
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodeorder 		= 'OUD-'.$year.''.$month.'-001';
				}else{
					$today 				= explode(' ', $codeorderpenjualan->created_at);
					$dateToday 			= substr($today[0], 0, -3);
					$allpenjualan 		= PenawaranbeeModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
					if($dateToday == date('Y-m')){
						$year 					= substr(date('Y'), -2);
						$newcode 				= count($allpenjualan) + 1;
						
						if($newcode > 0 && $newcode <= 9){
							$newSelectioncode 	= 'OUD-'.$year.''.date('m').'-00'.$newcode;
						}elseif($newcode > 9 && $newcode <= 99){
							$newSelectioncode 	= 'OUD-'.$year.''.date('m').'-0'.$newcode;
						}else{
							$newSelectioncode 	= 'OUD-'.$year.''.date('m').'-'.$newcode;
						}

						$lastSelection 			= PenawaranbeeModel::where('no_transaksi', $newSelectioncode)->get();
						if(count($lastSelection) > 0){
							$newcode2 			= $newcode + 1;
							if($newcode2 > 0 && $newcode2 <= 9){
								$newcodeorder 	= 'OUD-'.$year.''.date('m').'-00'.$newcode2;
							}elseif($newcode2 > 9 && $newcode2 <= 99){
								$newcodeorder 	= 'OUD-'.$year.''.date('m').'-0'.$newcode2;
							}else{
								$newcodeorder 	= 'OUD-'.$year.''.date('m').'-'.$newcode2;
							}
						}else{
							$newcodeorder 		= $newSelectioncode;
						}
					}else{
						$isToday 			= explode('-', date('Y-m-d'));
						$isYear 			= $isToday[0];
						$year 				= substr($isYear, -2);
						$month 				= $isToday[1];
						$day 				= $isToday[2];
						$newcodeorder 		= 'OUD-'.$year.''.$month.'-001';
					}
				}
			}

			$itemnull =	0;
			for ($i=0; $i < count($item); $i++) { 
				if($item[$i] == ''){
					$itemnull = $itemnull + 1;
				}
			}

			$qtynull =	0;
			for ($i=0; $i < count($qty); $i++) { 
				if($qty[$i] == ''){
					$qtynull = $qtynull + 1;
				}
				if($qty[$i] == 0){
					$qtynull = $qtynull + 1;
				}
			}

			$pricenull =	0;
			for ($i=0; $i < count($price); $i++) { 
				if($price[$i] == ''){
					$pricenull = $pricenull + 1;
				}
				if($price[$i] == 0){
					$pricenull = $pricenull + 1;
				}
			}

			if($itemnull > 0){
				echo goResult(false, 'Item is required');
				return;
			}

			if($qtynull > 0){
				echo goResult(false, 'Qty is required');
				return;
			}

			if($pricenull > 0){
				echo goResult(false, 'Price is required');
				return;
			}
	
			$totalprice 		= 0;
			$product 			= array();
			for ($i=0; $i < count($item); $i++) {

				$itemFinal  	= explode('-', $item[$i]);
				$iditem 		= $itemFinal[0];
				$codeitem 		= $itemFinal[1];
				$iditemFinal 	= '';
				$codeitemFinal 	= '';
				$nameitemFinal 	= '';
				$unititemFinal 	= '';

				if($this->input->post('database') == 'PT'){

					foreach ($data['masterItem']['data'] as $key => $value) {
						if($codeitem == $value->code){
							// dd($codeitem,$value->code);
							$iditemFinal 	= $iditemFinal.''.$value->id;
							$codeitemFinal 	= $codeitemFinal.''.$value->code;
							$nameitemFinal 	= $nameitemFinal.''.$value->name1;
							$unititemFinal 	= $unititemFinal.''.$value->purcunit;
						}
					}
						// dd('luar',$iditemFinal,$codeitemFinal,$nameitemFinal,$unititemFinal);
				}else{
					foreach ($data['masterItemUD']['data'] as $key => $value) {
						if($codeitem == $value->code){
							$iditemFinal 	= $iditemFinal.''.$value->id;
							$codeitemFinal 	= $codeitemFinal.''.$value->code;
							$nameitemFinal 	= $nameitemFinal.''.$value->name1;
							$unititemFinal 	= $unititemFinal.''.$value->purcunit;
						}
					}
				}

				$totalprice 	= $totalprice + $subtotal[$i];
				// dd($totalprice);
				if($disc[$i] != '0'){
					$discpercent 		= round((100 * ($price[$i] - $disc[$i])) / $price[$i]);

				}else{
					$discpercent 		= 0;
				}
				// dd($discpercent);

				if($ppn[$i] == '0'){
					$product[] 			= array(
						"baseprice" 			=> $price[$i],
						"basesubtotal" 			=> $price[$i] * $qty[$i],
						"conv" 					=> "1",
						"discamt" 				=> $disc[$i],
						"discexp" 				=> $discpercent,
						"dno" 					=> $i + 1,
						"item_id" 				=> $iditemFinal,
						"itemname" 				=> $nameitemFinal,
						"listprice" 			=> $price[$i],
						"qty" 					=> $qty[$i],
						"subtotal" 				=> $subtotal[$i],
						"taxableamt" 			=> "0",
						"taxamt" 				=> "0",
						"totaldiscamt" 			=> "0",
						"basetotaltaxamt" 		=> "0",
						"basefistotaltaxamt" 	=> "0",
						"calcmtd2" 				=> '',
						"calcmtd3" 				=> '',
						"calcmtd4" 				=> '',
						"dnote" 				=> '',
						"unit" 					=> $unititemFinal,
						"wh_id" 				=> $idgudangFinal
					);
				}else{
					$product[] 			= array(
						"baseprice" 			=> $price[$i],
						"basesubtotal" 			=> $price[$i] * $qty[$i],
						"conv" 					=> "1",
						"discamt" 				=> $disc[$i],
						"discexp" 				=> $discpercent,
						"dno" 					=> $i + 1,
						"item_id" 				=> $iditemFinal,
						"itemname" 				=> $nameitemFinal,
						"listprice" 			=> $price[$i],
						"qty" 					=> $qty[$i],
						"subtotal" 				=> $subtotal[$i],
						"tax_code" 				=> "PPN",
						"taxableamt" 			=> "0",
						"taxamt" 				=> "0",
						"totaldiscamt" 			=> "0",
						"basetotaltaxamt" 		=> "0",
						"basefistotaltaxamt" 	=> "0",
						"calcmtd2" 				=> '',
						"calcmtd3" 				=> '',
						"calcmtd4" 				=> '',
						"dnote" 				=> '',
						"unit" 					=> $unititemFinal,
						"wh_id" 				=> $idgudangFinal
					);
				}
				// dd($product);
			}

			if($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
				$taxed 		= true;
				$taxinc 	= true;
			}elseif($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '0'){
				$statusppn 	= '1';
				$includeppn = '0';
				$taxed 		= true;
				$taxinc 	= false;
			}elseif($this->input->post('ppn') == '0' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
				$taxed 		= true;
				$taxinc 	= true;
			}else{
				$statusppn 	= '0';
				$includeppn = '0';
				$taxed 		= false;
				$taxinc 	= false;
			}

			$mainproduct 	= array();
			$mainproduct[] 	= array(
				"bp_id" 	=> $idmitraFinal[0],
				"crc_id" 	=> "1",
				"sods" 		=> $product,
				"srep_id" 	=> $this->input->post('sales'),
				"branch_id" => $this->input->post('cabang'),
				"subtotal" 	=> $this->input->post('td_subtotal'),
				"taxamt" 	=> $this->input->post('td_ppn'),
				"taxed" 	=> $taxed,
				"taxinc" 	=> $taxinc,
				"total" 	=> $this->input->post('td_total'),
				"trxdate" 	=> $this->input->post('tgl_penawaran'),
				"trxno"		=> $newcodeorder
			);

			$dataInsert 		= array(
				"soarray" 		=> $mainproduct
			);
			dd($dataInsert);
			if($this->input->post('database') == 'PT'){
				$newpenawaran 		= ApiBee::postPenawaranPT($dataInsert);
				// dd(json_encode($newpenawaran));
			}else{
				$newpenawaran 		= ApiBee::postPenawaranUD($dataInsert);
			}

			if($newpenawaran['status'] != 200){
				echo goResult(false, $newpenawaran['msg']);
				return;
			}

			$penawaranbee 						= new PenawaranbeeModel;
			$penawaranbee->tgl_transaksi 		= $this->input->post('tgl_penawaran');
			$penawaranbee->name_db 				= $this->input->post('database');
			$penawaranbee->no_transaksi 		= $newcodeorder;
			$penawaranbee->id_sales 			= $this->input->post('sales');
			$penawaranbee->id_teknisi 			= $id_teknisi;
			$penawaranbee->id_cabang 			= $this->input->post('cabang');
			$penawaranbee->id_mitrabisnis 		= $idmitraFinal[0];
			$penawaranbee->code_mitrabisnis 	= $codemitraFinal[0];
			$penawaranbee->ppn 					= $this->input->post('td_ppn');
			$penawaranbee->status_ppn 			= $statusppn;
			$penawaranbee->status_includeppn 	= $includeppn;
			$penawaranbee->subtotal 			= $this->input->post('td_total');
			$penawaranbee->keterangan 			= $this->input->post('keterangan');
			$penawaranbee->id_statusorder 		= $this->input->post('status_order');

			if($penawaranbee->save()){
				for ($i=0; $i < count($item); $i++) {
					$itemFinal  	= explode('-', $item[$i]);
					$iditem 		= $itemFinal[0];
					$codeitem 		= $itemFinal[1];
					$iditemFinal 	= '';
					$codeitemFinal 	= '';
					$nameitemFinal 	= '';

					if($this->input->post('database') == 'PT'){
						foreach ($data['masterItem']['data'] as $key => $value) {
							if($codeitem == $value->code){
								$iditemFinal 	= $iditemFinal.''.$value->id;
								$codeitemFinal 	= $codeitemFinal.''.$value->code;
								$nameitemFinal 	= $nameitemFinal.''.$value->name1;
							}
						}
					}else{
						foreach ($data['masterItemUD']['data'] as $key => $value) {
							if($codeitem == $value->code){
								$iditemFinal 	= $iditemFinal.''.$value->id;
								$codeitemFinal 	= $codeitemFinal.''.$value->code;
								$nameitemFinal 	= $nameitemFinal.''.$value->name1;
							}
						}
					}

					$penawaranbeedetail 					= new PenawaranbeedetailModel;
					$penawaranbeedetail->id_penawaranbee 	= $penawaranbee->id;
					$penawaranbeedetail->id_gudang 			= $gudang;
					$penawaranbeedetail->id_item 			= $iditemFinal;
					$penawaranbeedetail->code_item 			= $codeitemFinal;
					$penawaranbeedetail->name_item 			= $nameitemFinal;
					$penawaranbeedetail->price 				= $price[$i];
					$penawaranbeedetail->qty 				= $qty[$i];
					$penawaranbeedetail->disc 				= $disc[$i];
					$penawaranbeedetail->subtotal 			= $subtotal[$i];
					$penawaranbeedetail->ppn 				= 0;
					$penawaranbeedetail->status_ppn 		= $ppn[$i];
					$penawaranbeedetail->save();
				}

				echo goResult(true, 'Order penjualan berhasil dibuat');
				return;
			}
	}

	public function order_penjualan_get(){
			$data['statusorder'] 		= MasterstatusorderModel::where('status', '1')->orderBy('name', 'asc')->get();
			$teknisi_id = $this->session->userdata('teknisi_id');
			$data['adminsales'] 		= TeknisiModel::where('status_regis', '1')->where('status_sales', '1')->asc()->get();
			$data['cabang'] = CabangModel::where('status','1')->asc()->get();
			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST
			if (!$teknisi_id) {

				$teknisi_id = $this->input->post('teknisi_id');

			}

			$data['menu'] 			= 'penawaranbee';
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$kodeFilter 			= $this->input->get('kode');
			$salesFilter 			= $this->input->get('sales');
			$customerFilter 		= $this->input->get('customer');
			$cabangFilter 			= $this->input->get('cabang');
			$checkdatevalue 		= $this->input->get('checkdatevalue');
			if(!$checkdatevalue){
				$checkdatefix 	= 'checked';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}

			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}

			if(!$tgl_awal){
				$startDate 		= date('Y-m-d', strtotime($lastDate. '-7 days'));
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}

			if(!$kodeFilter){
				$valuekode 		= '';
			}else{
				$valuekode 		= $kodeFilter;
			}

			if(!$salesFilter){
				$valuesales 	= 'all';
			}else{
				$valuesales 	= $salesFilter;
			}

			if(!$customerFilter){
				$valuecustomer 	= 'all';
			}else{
				$valuecustomer 	= $customerFilter;
			}

			if(!$cabangFilter){
				$valuecabang 	= 'all';
			}else{
				$valuecabang 	= $cabangFilter;
			}

			if($checkdatefix == 'checked'){
				if($valuesales != 'all' && $valuecustomer != 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('code_mitrabisnis', $valuecustomer)->where('id_cabang', $valuecabang)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer != 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('code_mitrabisnis', $valuecustomer)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer == 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('id_cabang', $valuecabang)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer != 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('code_mitrabisnis', $valuecustomer)->where('id_cabang', $valuecabang)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer == 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer != 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('code_mitrabisnis', $valuecustomer)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer == 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_cabang', $valuecabang)->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}else{
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->whereDate('tgl_transaksi', '>=', $startDate)->whereDate('tgl_transaksi', '<=', $lastDate)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}
			}else{
				if($valuesales != 'all' && $valuecustomer != 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('code_mitrabisnis', $valuecustomer)->where('id_cabang', $valuecabang)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer != 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('code_mitrabisnis', $valuecustomer)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer == 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('id_cabang', $valuecabang)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer != 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('code_mitrabisnis', $valuecustomer)->where('id_cabang', $valuecabang)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales != 'all' && $valuecustomer == 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_sales', $valuesales)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer != 'all' && $valuecabang == 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('code_mitrabisnis', $valuecustomer)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}elseif($valuesales == 'all' && $valuecustomer == 'all' && $valuecabang != 'all'){
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('id_cabang', $valuecabang)->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}else{
					$penawaran  = PenawaranbeeModel::where('status_deleted', '0')->where('no_transaksi', 'LIKE', '%'.$valuekode.'%')->orderBy('tgl_transaksi', 'desc')->get();
				}
			}

			$page 						= $this->uri->segment(5);
			if(!is_numeric($page)){
				$page 					= 0;
			}

			$idPenawaran 				= array();
			foreach ($penawaran as $value) {
				$idPenawaran[] 			= $value->id;
			}

			$paginate					= new Myweb_pagination;
			$total						= count($idPenawaran);
			$data['teknisi']			= TeknisiModel::where('id',$teknisi_id)->first();
			
			$data['jabatan']			= JabatanModel::where('id',$data['teknisi']->id_jabatan)->get();
			$data['penawaran'] 			= PenawaranbeeModel::whereIn('id', $idPenawaran)->take(20)->skip($page*20)->orderBy('tgl_transaksi', 'desc')->get();
			$data['numberpage'] 		= $page*20;
			
			$data['tgl_awal'] 			= $startDate;
			$data['tgl_akhir'] 			= $lastDate;
            $data['nopenjualan'] = PenjualanModel::whereBetween('tgl_transaksi', [$startDate, $lastDate])->get();

            $data['mitraBisnis'] = CustomernewModel::where('status_deleted',0)->get();
			$data['checkdatevalue'] 	= $checkdatefix;
			$data['kodeFilter'] 		= $valuekode;
			$data['salesFilter'] 		= $valuesales;
			$data['customerFilter'] 	= $valuecustomer;
			$data['cabangFilter'] 		= $valuecabang;
			$data['option'] 			= PenjualanoptionModel::where('status', '1')->orderBy('name', 'asc')->get();
			echo goResult(true,$data);
			return;
	}

	public function order_penjualan_viewtambah_get()
	{
	    $teknisi_id = $this->session->userdata('teknisi_id');

	    // Cek teknisi_id dalam session atau dari POST
	    if (!$teknisi_id) {
	        $teknisi_id = $this->input->post('teknisi_id');
	    }

	    // Ambil data cabang dan gudang
	    $data['cabang'] = CabangModel::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
	    $data['gudang'] = GudangModel::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
	    $data['adminsales'] = TeknisiModel::where('status_regis', '1')
	    ->where('status_sales', '1')
	    ->select('id', 'name') // Hanya mengambil kolom id dan name
	    ->orderBy('id', 'asc') // Mengurutkan berdasarkan id
	    ->get();

	    // Ambil data teknisi
	    $data['allteknisi'] = TeknisiModel::select('id', 'name')
	        // ->where('status', 'teknisi')
	        ->where('status_regis', 1)
	        ->orderBy('name', 'asc')
	        ->get();

	    // Ambil data status order
	    $data['statusorder'] = MasterstatusorderModel::select('id', 'name')
	        ->where('status', '1')
	        ->orderBy('name', 'asc')
	        ->get();

	    // Ambil data penjualan option
	    $data['option'] = PenjualanoptionModel::select('id', 'name')
	        ->where('status', '1')
	        ->orderBy('name', 'asc')
	        ->get();

	    // Ambil data mitra bisnis PT dan UD, gabungkan tanpa duplikasi
	    $mitraBisnisPT = ApiBee::getMitrabisnisPT();
	    $mitrabisnisUD = ApiBee::getMitrabisnisUD();

	    $filteredDataPT = isset($mitraBisnisPT['data']) && is_array($mitraBisnisPT['data'])
	        ? array_map(function ($item) {
	            return [
	                'id' => $item->id ?? null,
	                'code' => $item->code ?? null,
	                'name' => $item->name ?? null,
	                'saletax_code' => $item->saletax_code ?? null,
	                'purctax_code' => $item->purctax_code ?? null,
	                'address' => $item->address ?? null,
	                'namecont' => $item->namecont ?? null,
	                'mobile' => $item->mobile ?? null,
	            ];
	        }, $mitraBisnisPT['data']) : [];

	    $filteredDataUD = isset($mitrabisnisUD['data']) && is_array($mitrabisnisUD['data'])
	        ? array_map(function ($itemUD) {
	            return [
	                'id' => $itemUD->id ?? null,
	                'code' => $itemUD->code ?? null,
	                'name' => $itemUD->name ?? null,
	                'saletax_code' => $itemUD->saletax_code ?? null,
	                'purctax_code' => $itemUD->purctax_code ?? null,
	                'address' => $itemUD->address ?? null,
	                'namecont' => $itemUD->namecont ?? null,
	                'mobile' => $itemUD->mobile ?? null,
	            ];
	        }, $mitrabisnisUD['data']) : [];

	    $names = [];
	    $combinedData = array_filter(array_merge($filteredDataPT, $filteredDataUD), function ($item) use (&$names) {
	        if (in_array($item['name'], $names)) {
	            return false;
	        }
	        $names[] = $item['name'];
	        return true;
	    });

	    $data['combinedMitraBisnis'] = $combinedData;

	    // Ambil data master item dan harga item dari PT dan UD
	    // $data['masterItem'] = ApiBee::getMasterItemPT();
	    // $data['masterItemUD'] = ApiBee::getMasterItemUD();
	    $this->db->select('id, new_kode, name, name_english, name_alias') // Pilih kolom yang dibutuhkan
		    ->from('barang') // Nama tabel
		    ->where('status_deleted', '0') // Kondisi
		    ->order_by('status_check', 'desc') // Urutkan berdasarkan status_check descending
		    ->order_by('new_kode', 'asc'); // Urutkan berdasarkan new_kode ascending
		$data['masterItem'] = $this->db->get()->result(); // Eksekusi query dan ambil hasil

		// $this->db->select('id,id_barang,name')

	    $hargaItem = ApiBee::getHargaItemPT();
	    $hargaItemUD = ApiBee::getHargaItemUD();
	    $filteredDataHargaPT = isset($hargaItem['data']) && is_array($hargaItem['data'])
	        ? array_map(function ($item) {
	            return [
	                'item_id' => $item->item_id ?? null,
	                'price1' => $item->price1 ?? null,
	                'itemid' => $item->itemid ?? null,
	                'price2' => $item->price2 ?? null,
	               
	            ];
	        }, $hargaItem['data']) : [];

	    $filteredDataHargaUD = isset($hargaItemUD['data']) && is_array($hargaItemUD['data'])
	        ? array_map(function ($itemUD) {
	            return [
	                'item_id' => $itemUD->item_id ?? null,
	                'price1' => $itemUD->price1 ?? null,
	                'itemid' => $itemUD->itemid ?? null,
	                'price2' => $itemUD->price2 ?? null,
	                
	            ];
	        }, $hargaItemUD['data']) : [];

	    $names = [];
	    $combinedDataHarga = array_filter(array_merge($filteredDataHargaPT, $filteredDataHargaUD), function ($item) use (&$names) {
	        if (in_array($item['item_id'], $names)) {
	            return false;
	        }
	        $names[] = $item['item_id'];
	        return true;
	    });
	    $data['hargaItem'] = $combinedDataHarga;
	    echo goResult(true, $data);
	    return;
	}
	public function order_penjualan_viewedit_post(){
		$id = $this->input->post('code');

		 $teknisi_id = $this->session->userdata('teknisi_id');

	    // Cek teknisi_id dalam session atau dari POST
	    if (!$teknisi_id) {
	        $teknisi_id = $this->input->post('teknisi_id');
	    }
	    $data['type'] 		= 'update';
			$data['menu'] 		= 'penawaranbee';
			
			//MITRA BISNIS
			// $data['mitraBisnis'] 	= ApiBee::getMitrabisnisPT();
			// $data['mitraBisnisUD'] 	= ApiBee::getMitrabisnisUD();
			
			// //ITEM
			// $data['masterItem'] 	= ApiBee::getMasterItemPT();
			// $data['masterItemUD'] 	= ApiBee::getMasterItemUD();
			
			// //HARGA ITEM
			// // $data['hargaItem'] 		= ApiBee::getHargaItemPT();
			// // $data['hargaItemUD'] 	= ApiBee::getHargaItemUD();
			
			// //GUDANG
			// $data['gudang'] 		= ApiBee::getGudangPT();
			// $data['gudangUD'] 		= ApiBee::getGudangUD();
			  // Ambil data penawaran
		    $penawaran = PenawaranbeeModel::where('no_transaksi', $id)->first();
		    if (!$penawaran) {
		        echo goResult(['error' => 'Penawaran tidak ditemukan'], 404);
		        return;
		    }

		    $data['penawaran'] = $penawaran;

		    // Ambil detail penawaran
		    $details = PenawaranbeedetailModel::where('id_penawaranbee', $penawaran->id)
		        ->orderBy('id', 'desc') // Pastikan urutan sesuai kebutuhan
		        ->get();

		     

		    // Validasi detail penawaran
		    if ($details->isEmpty()) {
		        $data['detail'] = [];
		    } else {
		        $data['detail'] = $details;
		    }

		    $foto = array();
		    foreach ($details as $key_detail => $value) {
		    	$foto[] = $value->code_item;
		    }

		    $data['customer_new'] = CustomernewModel::where('code',$penawaran->code_mitrabisnis)->get();
		    $data['sales']         = TeknisiModel::where('id_bee', $penawaran->id_sales)->asc()->first();
		    $data['foto']			= BarangModel::whereIn('new_kode',$foto)->get();
		    echo goResult(true,$data);
		    return;
	}
	// public function order_penjualan_edit_post(){
	// 	$id = $this->input->post('id');
	// 	 $teknisi_id = $this->session->userdata('teknisi_id');

	//     // Cek teknisi_id dalam session atau dari POST
	//     if (!$teknisi_id) {
	//         $teknisi_id = $this->input->post('teknisi_id');
	//     }
	//         $data['type'] 		= 'update';
	// 		$data['menu'] 		= 'penawaranbee';
			
	// 		//MITRA BISNIS
	// 		// $data['mitraBisnis'] 	= ApiBee::getMitrabisnisPT();
	// 		// $data['mitraBisnisUD'] 	= ApiBee::getMitrabisnisUD();
			
	// 		// //ITEM
	// 		// $data['masterItem'] 	= ApiBee::getMasterItemPT();
	// 		// $data['masterItemUD'] 	= ApiBee::getMasterItemUD();
			
	// 		// //HARGA ITEM
	// 		// $data['hargaItem'] 		= ApiBee::getHargaItemPT();
	// 		// $data['hargaItemUD'] 	= ApiBee::getHargaItemUD();
			
	// 		// //GUDANG
	// 		// $data['gudang'] 		= ApiBee::getGudangPT();
	// 		// $data['gudangUD'] 		= ApiBee::getGudangUD();
	// 		$data['penawaran'] 	= PenawaranbeeModel::where('no_transaksi',$id)->first();
	// 		$data['detail'] 	= PenawaranbeedetailModel::where('id_penawaranbee', $data['penawaran']->id)->desc()->get();
	// 		$data['customer_new'] = CustomernewModel::wh
			
	// 		$id_gudang 			= '';
	// 		foreach ($data['detail'] as $key => $value) {
	// 			$id_gudang 		= '';
	// 			$id_gudang 		= $id_gudang.''.$value->id_gudang;
	// 		}

	// 		$data['id_gudang'] 	= $id_gudang;
	// 		echo goResult(true, $data);
	// 		return;
	// }

	public function order_penjualan_menambah_post(){
		$teknisi_id = $this->session->userdata('teknisi_id');

			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



			if (!$teknisi_id) {

				$teknisi_id = $this->input->post('teknisi_id');

			}
			$data['mitraBisnis'] 	= ApiBee::getMitrabisnisPT();
			$data['mitraBisnisUD'] 	= ApiBee::getMitrabisnisUD();
			
			//ITEM
			$data['masterItem'] 	= ApiBee::getMasterItemPT();
			$data['masterItemUD'] 	= ApiBee::getMasterItemUD();
			
			//HARGA ITEM
			$data['hargaItem'] 		= ApiBee::getHargaItemPT();
			$data['hargaItemUD'] 	= ApiBee::getHargaItemUD();
			
			//GUDANG
			$data['gudang'] 		= ApiBee::getGudangPT();
			$data['gudangUD'] 		= ApiBee::getGudangUD();

			$PenawranBee = new PenawaranbeeModel;

			$tgl_transaksi = $this->input->post('tgl_request');
			$name_db = $this->input->post('database');
			// $tgl_transaksi = $this->input->post('tgl_request');
			$tgl_transaksi = $this->input->post('tgl_request');
	}







	//API Fitur Penawaran



	public function penawaran_get()

	{



		$tgl_awal 				= $this->input->get('tgl_awal');



		$tgl_akhir 				= $this->input->get('tgl_akhir');



		$checkdatevalue 		= $this->input->get('checkdatevalue');



		$nameFilter 			= $this->input->get('name');



		if (!$checkdatevalue) {



			$checkdatefix 		= 'checked';

		} else {



			$checkdatefix 		= $checkdatevalue;

		}



		if (!$tgl_akhir) {



			$lastDate 			= date('Y-m-d');

		} else {



			if ($tgl_akhir == '') {



				$lastDate 		= date('Y-m-d');

			} else {



				$lastDate 		= $tgl_akhir;

			}

		}



		if (!$tgl_awal) {



			$startDate 		= date('Y-m-d', strtotime($lastDate . '-7 days'));
		
		} else {



			if ($tgl_awal == '') {



				$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

			} else {



				$startDate 	= $tgl_awal;

			}

		}



		if (!$nameFilter) {



			$valuename 				= '';

		} else {



			$valuename 				= $nameFilter;

		}



		if ($checkdatefix == 'checked') {


			
			$penawaran = PenawaranModel::where('company', 'like', '%' . $valuename . '%')



				->whereDate('created_at', '>=', $startDate)



				->whereDate('created_at', '<=', $lastDate)



				->desc()



				->get();
			
		} else {



			$penawaran 				= PenawaranModel::where('company', 'like', '%' . $valuename . '%')->desc()->get();

		}



		$idpenawaran 				= array();



		foreach ($penawaran as $key => $value) {



			$idpenawaran[] 			= $value->id;

		}

			

	





		$total						= count($penawaran);

		// dd($total);
		if($total >0){
			$data['penawaran'] 			= PenawaranModel::with('detail')->whereIn('id', $idpenawaran)->desc()->get();

			$penawaran_detail = PenawaranDetailModel::whereIn('id_penawaran',$idpenawaran)->get();


			$id_barang_new = [];
			$id_penawaran_new = [];
			foreach ($penawaran_detail as $detail) {
			    $id_barang_new[] = $detail->id_barang;
			    $id_penawaran_new[] = $detail->id_penawaran;
			}
			
			$this->db->select('penawaran_detail.*, barang.*');
			$this->db->from('penawaran_detail');
			$this->db->join('barang', 'penawaran_detail.id_barang = barang.id', 'inner');
			$this->db->where_in('penawaran_detail.id_barang', $id_barang_new);
			$this->db->where_in('penawaran_detail.id_penawaran', $id_penawaran_new);
			$query = $this->db->get();

			$data['detail_penawaran'] = $query->result_array();

			$this->db->select('id, name, new_kode,spesification');



			$this->db->from('barang');



			$this->db->where('status_deleted', '0');



			$this->db->order_by('name', 'asc');



			$query = $this->db->get();



			$data['product'] = $query->result_array();

			$data['barang_price'] = BarangPriceModel::get()->map(function($item) {
				return [
					'id' => $item->id,
					'id_barang' => $item->id_barang,
					'price_list' => $item->price_list
				];
			});

		}

			
		
		else{
			$data['penawaran'] = collect(); // If using Laravel collections; otherwise, use []
		    $data['detail_penawaran'] = [];
		    $data['product'] = [];
		    $data['barang_price'] = [];
		}






		$data['tgl_awal'] 		= $startDate;



		$data['tgl_akhir'] 		= $lastDate;



		$data['nameFilter'] 	= $valuename;



		$data['checkdatevalue'] = $checkdatefix;

		// $penawaran_detail

		echo goResult(true, $data);



		return;

	}







	public function penawaran_viewedit_get()

	{







		$id = $this->input->get('id');



		$data['penawaran'] = PenawaranModel::join('account', 'penawaran.id_account', '=', 'account.id')



			->select('penawaran.*', 'account.name as name_account', 'account.bank_name as name_bank', 'account.bank_number as number_bank', 'account.bank_account as account_bank')->where('penawaran.id', $id)->first();



		if (!empty($data['penawaran']) || $data['penawaran'] != null) {







			//mengambil data barang dari model penawaran dengan inner join penawaran_detail dan barang



			$data['penawaran_barang'] = PenawaranModel::with('detail')->join('penawaran_detail', 'penawaran.id', '=', 'penawaran_detail.id_penawaran')



				->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id')



				->where('penawaran.id', $id)



				->select('barang.name', 'barang.id', 'barang.image','barang.new_kode')



				->get();







			//mengamil data barang dari tabel barang melalui query builder ci 3



			$this->db->select('id, name, new_kode,spesification');



			$this->db->from('barang');



			$this->db->where('status_deleted', '0');



			$this->db->order_by('name', 'asc');



			$query = $this->db->get();



			$data['product'] = $query->result_array();




			$data['barang_price'] = BarangPriceModel::get()->map(function($item) {
				return [
					'id' => $item->id,
					'id_barang' => $item->id_barang,
					'price_list' => $item->price_list
				];
			});



			//mengambil data dari tabel penawaran detail



			$this->db->select('*');



			$this->db->from('penawaran_detail');



			$this->db->where('penawaran_detail.id_penawaran', $id);



			$query = $this->db->get();



			$data['penawaran_detail'] = $query->result_array();



			$lastDate 		= date('Y-m-d');



			$data['date'] = $lastDate;



			echo goResult(true, $data);



			return;

		} else {



			http_response_code(404);



			echo goResult(false, 'Data not found');



			return;

		}

	}







	public function edit_penawaran_post()

	{



		$id = $this->input->post('id');







		$rules 		= [



			'required' 	=> [



				['name'], ['address'], ['handphone'], ['email'], ['pembayaran']



			]



		];



		$validate 	= Validation::check($rules, 'post');



		if (!$validate->auth) {



			echo goResult(false, $validate->msg);



			return;

		}



		$product 					= $this->input->post('idProduct');



		if (count($product) <= 0) {



			echo goResult(false, 'Produk belum dipilih');



			return;

		}



		$penawaran 					= PenawaranModel::find($id);



		$penawaran->name			= $this->input->post('name');



		$penawaran->company			= $this->input->post('company');



		$penawaran->address			= $this->input->post('address');



		$penawaran->handphone		= $this->input->post('handphone');



		$penawaran->email			= $this->input->post('email');



		$penawaran->id_account		= $this->input->post('pembayaran');



		$penawaran->text_top		= $this->input->post('text_top');



		$penawaran->text_middle		= $this->input->post('text_middle');



		$penawaran->text_bottom		= $this->input->post('text_bottom');



		$penawaran->text_bothbottom	= $this->input->post('text_bothbottom');



		$penawaran->ppn				= $this->input->post('statusppn');



		if ($penawaran->save()) {



			PenawaranDetailModel::where('id_penawaran', $penawaran->id)->delete();



			$product 				= $this->input->post('idProduct');



			// dd($penawaran->id,$product);



			$price 					= $this->input->post('priceProduct');



			$spesification 			= $this->input->post('spesificationProduct');



			if ($product) {



				for ($i = 0; $i < count($product); $i++) {



					$insertProduct 					= new PenawaranDetailModel;



					$insertProduct->id_penawaran	= $penawaran->id;



					$insertProduct->id_barang 		= $product[$i];



					$insertProduct->price 			= (int) str_replace('.', '', $price[$i]);



					$insertProduct->spesification 	= $spesification[$i];



					$insertProduct->save();

				}

			}



			$data['idPenawaran'] 		= $penawaran->id;



			$data['auth'] 				= true;



			$data['msg'] 				= 'Penawaran telah berhasil diubah';



			echo toJson($data);



			return;

		}

	}







	public function tambah_penawaran_post()

	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Teknisi tidak ada'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);



		// dd($teknisi->code_name);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules 		= [



			'required' 	=> [



				['name'], ['address'], ['handphone'], ['email'], ['pembayaran']



			]



		];



		$validate 	= Validation::check($rules, 'post');



		if (!$validate->auth) {



			echo goResult(false, $validate->msg);



			return;

		}



		$product 					= $this->input->post('idProduct');



		if (count($product) <= 0) {



			echo goResult(false, 'Produk belum dipilih');



			return;

		}



		$firstPenawaran 		= PenawaranModel::desc()->first();



		if (!$firstPenawaran) {



			$isToday 			= explode('-', date('Y-m-d'));



			$isYear 			= $isToday[0];



			$year 				= substr($isYear, -2);



			$month 				= $isToday[1];



			$day 				= $isToday[2];



			$code 				= $year . '' . $month . '' . $day . '01';

		} else {



			$today 		= explode(' ', $firstPenawaran->created_at);



			if ($today[0] == date('Y-m-d')) {



				$codeex 		= explode('/', $firstPenawaran->code);



				$code 			= $codeex[1] + 1;

			} else {



				$isToday 		= explode('-', date('Y-m-d'));



				$isYear 		= $isToday[0];



				$year 			= substr($isYear, -2);



				$month 			= $isToday[1];



				$day 			= $isToday[2];



				$code 			= $year . '' . $month . '' . $day . '01';

			}

		}



		$penawaran 					= new PenawaranModel;







		$penawaran->id_teknisi		= $teknisi->id;



		// dd($teknisi_id)



		$penawaran->code			= 'SPN/' . $code . '/' . $teknisi->code_name;



		$penawaran->name			= $this->input->post('name');



		$penawaran->company			= $this->input->post('company');



		$penawaran->address			= $this->input->post('address');



		$penawaran->handphone		= $this->input->post('handphone');



		$penawaran->email			= $this->input->post('email');



		$penawaran->id_account		= $this->input->post('pembayaran');



		$penawaran->text_top		= $this->input->post('text_top');



		$penawaran->text_middle		= $this->input->post('text_middle');



		$penawaran->text_bottom		= $this->input->post('text_bottom');



		$penawaran->text_bothbottom	= $this->input->post('text_bothbottom');



		$penawaran->ppn				= $this->input->post('statusppn');



		if ($penawaran->save()) {



			$product 				= $this->input->post('idProduct');



			$price 					= $this->input->post('priceProduct');



			$spesification 			= $this->input->post('spesificationProduct');



			if ($product) {



				for ($i = 0; $i < count($product); $i++) {



					$insertProduct 					= new PenawaranDetailModel;



					$insertProduct->id_penawaran	= $penawaran->id;



					$insertProduct->id_barang 		= $product[$i];



					$insertProduct->price 			= (int) str_replace('.', '', $price[$i]);



					$insertProduct->spesification 	= $spesification[$i];



					$insertProduct->save();

				}

			}



			$data['idPenawaran'] 		= $penawaran->id;



			$data['auth'] 				= true;



			$data['msg'] 				= 'Penawaran telah berhasil dibuat';



			$data['msg2'] 				= $teknisi_id;



			echo toJson($data);



			return;

		}

	}







	public function hapus_penawaran_delete($id)



	{







		if (!$id) {



			$id = $this->input->get('id');

		}



		// Cek apakah penawaran ada



		$penawaran = $this->db->get_where('penawaran', ['id' => $id])->row();







		if (!$penawaran) {



			echo goResult(false, 'Maaf, penawaran tidak ada');



			return;

		}







		// Memulai transaksi database



		$this->db->trans_begin();







		try {



			// Hapus detail penawaran terlebih dahulu



			$this->db->where('id_penawaran', $id);



			$this->db->delete('penawaran_detail');







			// Hapus penawaran



			$this->db->where('id', $id);



			$this->db->delete('penawaran');







			// Komit transaksi jika semuanya berjalan baik



			if ($this->db->trans_status() === FALSE) {



				// Jika ada kesalahan, rollback transaksi



				$this->db->trans_rollback();



				echo goResult(false, 'Gagal menghapus data');

			} else {



				// Jika tidak ada kesalahan, komit transaksi



				$this->db->trans_commit();



				echo goResult(true, 'Data anda berhasil dihapus');

			}

		} catch (Exception $e) {



			// Rollback transaksi jika ada pengecualian



			$this->db->trans_rollback();



			echo goResult(false, 'Gagal menghapus data: ' . $e->getMessage());

		}







		return;

	}







	public function restok_get()

	{



		$tgl_awal 						= $this->input->get('tgl_awal');



		$tgl_akhir 						= $this->input->get('tgl_akhir');



		$kode_barang 					= $this->input->get('kode_barang');



		$nama_barang 					= $this->input->get('nama_barang');



		$jenis 							= $this->input->get('jenis');



		$status 						= $this->input->get('status');

		// dd($status)

		$checkdatevalue 				= $this->input->get('checkdatevalue');



		if (!$checkdatevalue) //bila kotak filter tidak dicentang



		{



			$checkdatefix = '';

		} else {



			$checkdatefix = $checkdatevalue;







			//checkdatefix sama dengan checkdate centang







		}



		if (!$tgl_akhir) {



			$lastDate 		= date('Y-m-d');



			//tanggal terakhir filter hari ini







		} else {



			//tanggal terakhir filter bila dipilih







			if ($tgl_akhir == '') {



				$lastDate 	= date('Y-m-d');

			} else {



				$lastDate 	= $tgl_akhir;

			}

		}



		if (!$tgl_awal) {



			//tgl_awal kosong karna kotak filter tidak dicentang, maka mengambil status requested berdasarakan tgl_request paling awal



			$startDate = RestokModel::where('status', 'requested')



				->orderBy('tgl_request', 'asc')



				->value('tgl_request');







			if ($startDate) {



				$startDate = Carbon::parse($startDate)->format('Y-m-d');



				//mengkonversi tgl_requested dengan format indonesia



			}

		} else {



			//tgl awal tidak kosong karna kotak filter dicentang, maka mengambil 7 hari dari tgl hari ini



			if ($tgl_awal == '') {



				$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

			} else {



				$startDate 	= $tgl_awal;

			}

		}



		$statusnull = 0;



		if ($status) {



			for ($i = 0; $i < count($status); $i++) {



				if ($status[$i] == 'semua') {



					$statusnull = $statusnull + 1;

				}

			}

		}



		if ($checkdatefix == 'checked') {



			if (!$status) {





				//checkdate checked dan status null maka (default requested)

				$restok 		= RestokModel::where('status_deleted', '0')->where('status', '!=', 'complete')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->groupBy('id_barang')->desc()->get();

			} else {







				if ($statusnull > 0) {



					//checkdate checked dan status tidak null (status semua)

					// dd('semua');

					$restok 		= RestokModel::where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->groupBy('id_barang')->desc()->get();

				} else {



					//checkdate checked dan status tidak nukk (status dipilih)





					$restok 		= RestokModel::where('status_deleted', '0')->where('status', $status)->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->groupBy('id_barang')->desc()->get();

				}

			}

		} else {







			if (!$status) {



				//checkdate tidak ada dan status null (default requested)



				$restok 		= RestokModel::whereNotIn('status', 'complete')



					->whereDate('tgl_request', '>=', $startDate)



					->whereDate('tgl_request', '<=', $lastDate)



					->orderBy('id_barang', 'desc')



					->get();

			} else {



				if ($statusnull > 0) {



					//checkdate tidak ada dan status semua (fix)



					// $restok 		= RestokModel::where('status_deleted', '0')->where('status', '!=', 'complete')->groupBy('id_barang')->desc()->get();



					$restok 		= RestokModel::where('status_deleted', '0')->groupBy('id_barang')->desc()->get();

				} else {



					// checkdate tidak ada dan status dipilih (fix)



					$restok 		= RestokModel::where('status_deleted', '0')->where('status', $status)->groupBy('id_barang')->desc()->get();

				}

			}

		}



		$idProduct 			= array();







		foreach ($restok as $key => $value) {



			$idProduct[] 	= $value->id_barang;



			//mengambil id_barang untuk disimpan ke array



		}



		$data['statusarray'] = [];







		if (!$status) {



			$datanull = '';



			array_push($data['statusarray'], $datanull);



			//kalau status null maka ditambahkan array statusarray adalah null



		} else {



			for ($i = 0; $i < count($status); $i++) {



				array_push($data['statusarray'], $status[$i]);

			}

		}











		$kodeBarang = $kode_barang ?: ''; //mengecek apakah kode barang kosong







		$namaBarang = $nama_barang ?: ''; //mengecek apakah nama barang kososng







		$productQuery = BarangModel::whereIn('id', $idProduct); //mengambil barang kalau nama dan kode barang kosong berdasarkan idProduct







		if ($nama_barang) {



			$productQuery->where('name', 'LIKE', '%' . $nama_barang . '%'); //kalau nama barang ada



		}







		if ($kode_barang) {



			$productQuery->where('new_kode', $kode_barang); // kalau kode barang ada



		}







		$product = $productQuery->get(); //mengambil barang berdasarkan kondisi yang memenuhi











		$productjenis 			= array();







		foreach ($product as $key => $value) {



			$productjenis[] 	= $value->id;

		}







		if (!$jenis) {



			// kalau jenis kosong maka dipilih all



			$jenisValue 		= 'all';



			$productbyjenis 	= BarangModel::whereIn('id', $productjenis)->get();

		} else {







			// jika jenis all



			if ($jenis == 'all') {



				$jenisValue 	= 'all';



				$productbyjenis = BarangModel::whereIn('id', $productjenis)->get();

			} else {



				$jenisValue 	= $jenis;



				$productbyjenis = BarangModel::whereIn('id', $productjenis)->where('id_category', $jenis)->get();

			}

		}







		$fixproduct 			= array();



		foreach ($productbyjenis as $key => $value) {



			$fixproduct[] 		= $value->id;

		}







		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {



			$page 					= $this->input->get('page');

		}







		$paginate					= new Myweb_pagination;



		if ($checkdatefix == 'checked') {



			if (!$status) { //kondisi checkdate checkked dan status null (default requested)

				// dd('ab');

				$total					= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();



				// $data['restok'] 		= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();







				$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







					->join('barang', 'restok.id_barang', '=', 'barang.id')



					->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



					->whereIn('restok.id_barang', $fixproduct)



					->whereNotIn('restok.status', ['complete'])



					->where('restok.status_deleted', '0')



					->whereDate('restok.tgl_request', '>=', $startDate)



					->whereDate('restok.tgl_request', '<=', $lastDate)



					->orderBy('restok.tgl_request', 'desc')



					->get()

					->map(function ($item) {
				        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
				        return $item;
				    });

			} else {







				if ($statusnull > 0) { //kondisi checkdate checkked dan status tidak null (semua)





					$total				= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();



					// $data['restok'] 	= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();

					// $statusArray = explode(',', $status);

					$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







						->join('barang', 'restok.id_barang', '=', 'barang.id')



						->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



						->whereIn('restok.id_barang', $fixproduct)







						->where('restok.status_deleted', '0')



						->whereDate('restok.tgl_request', '>=', $startDate)



						->whereDate('restok.tgl_request', '<=', $lastDate)



						->orderBy('restok.tgl_request', 'desc')



						->get()
						->map(function ($item) {
					        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
					        return $item;
					    });

				} else {



					//

					$total				= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();



					// $data['restok'] 	= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->desc()->get();



					//kondisi checkdate checkked dan status tidak null (status dipilih)

					$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







						->join('barang', 'restok.id_barang', '=', 'barang.id')



						->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



						->whereIn('restok.id_barang', $fixproduct)



						->whereIn('restok.status', [$status])



						->where('restok.status_deleted', '0')



						->whereDate('restok.tgl_request', '>=', $startDate)



						->whereDate('restok.tgl_request', '<=', $lastDate)



						->orderBy('restok.tgl_request', 'desc')



						->get()
						->map(function ($item) {
					        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
					        return $item;
					    });

				}

			}

		} else {



			if (!$status) {







				$total					= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->desc()->get();



				// $data['restok'] 		= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '=', 'requested')->where('status_deleted', '0')->desc()->get();







				$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







					->join('barang', 'restok.id_barang', '=', 'barang.id')



					->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



					->whereIn('restok.id_barang', $fixproduct)



					->whereNotIn('restok.status', ['complete'])



					->where('restok.status_deleted', '0')





					->orderBy('restok.tgl_request', 'desc')



					->get()
					->map(function ($item) {
					        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
					        return $item;
					});

			} else {



				if ($statusnull > 0) {



					//checkdate tidak ada dan status semua



					$total				= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->desc()->get();



					// $data['restok'] 	= RestokModel::whereIn('id_barang', $fixproduct)->where('status', '!=', 'complete')->where('status_deleted', '0')->desc()->get();







					$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







						->join('barang', 'restok.id_barang', '=', 'barang.id')



						->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



						->whereIn('restok.id_barang', $fixproduct)





						->where('restok.status_deleted', '0')





						->orderBy('restok.tgl_request', 'desc')



						->get()
						->map(function ($item) {
					        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
					        return $item;
					    });

				} else {



					$total				= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status)->where('status_deleted', '0')->desc()->get();



					//checkdate tidak ada dan status dipilih

					$data['restok'] = RestokModel::select('restok.*', 'barang.new_kode as new_kode', 'barang.id as barang_id', 'barang.name as nama_barang', 'barang.image as image', 'teknisi.id as teknisi_id', 'teknisi.name as name_teknisi', 'barang.cbm as kubik')







						->join('barang', 'restok.id_barang', '=', 'barang.id')



						->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')



						->whereIn('restok.id_barang', $fixproduct)



						->whereIn('restok.status', [$status])



						->where('restok.status_deleted', '0')





						->orderBy('restok.tgl_request', 'desc')



						->get()
						->map(function ($item) {
					        $item['image_url'] = 'https://maxipro.id/images/barang/' . $item['image']; // Assuming $item['image'] contains the image path
					        return $item;
					    });

				}

			}

		}











		$data['numberpage'] 		= $page * 20;





		$data['category'] = BarangcategoryModel::select('id', 'name')

			->where('status', '1')

			->orderBy('id', 'asc')

			->get();

		$this->db->select('id, name, new_kode');



		$this->db->from('barang');



		$this->db->where('status_deleted', '0');



		$this->db->order_by('name', 'asc');



		$query = $this->db->get();



		$data['product'] = $query->result_array();





		$data['tgl_awal'] 			= $startDate;



		$data['tgl_akhir'] 			= $lastDate;



		$data['kode_barang'] 		= $kodeBarang;



		$data['nama_barang'] 		= $namaBarang;



		$data['jenis'] 				= $jenisValue;



		$data['checkdatevalue'] 	= $checkdatefix;









		$updatelaststok 			= $this->updateRestokLiveNotAjax();



		echo goResult(true, $data);



		return;

	}



	public function restok_viewstokproduct_get($id_product)

	{



		$data['restok'] = RestokModel::where('id_barang', $id_product)->first();



		$product 			= BarangModel::find($data['restok']->id_barang);

		$codeproduct 		= $product->new_kode;

		//PT

		$masterItemPT 		= ApiBee::getMasterItemPT();

		$forStokPT 			= ApiBee::getStokPT();

		$forGudangPT 		= ApiBee::getGudangPT();

		$arrayStokPT 		= array();

		$countStokPT 		= 0;

		foreach ($forStokPT['data'] as $valueStokPT) {

			if ($valueStokPT->itemid == $codeproduct) {

				$countStokPT 	= $countStokPT + 1;

				foreach ($forGudangPT['data'] as $valueGudangPT) {

					if ($valueGudangPT->id == $valueStokPT->wh_id) {

						$arrayStokPT[] 		= array(

							'name_gudang' 	=> $valueGudangPT->name,

							'stok_gudang' 	=> number_format($valueStokPT->qty)

						);

					}

				}

			} else {

			}

		}

		//UD

		$formasterItemUD 	= ApiBee::getMasterItemUD();

		$forStokUD 			= ApiBee::getStokUD();

		$forGudangUD 		= ApiBee::getGudangUD();

		$arrayStokUD 		= array();

		$countStokUD 		= 0;

		foreach ($forStokUD['data'] as $valueStokUD) {

			if ($valueStokUD->itemid == $codeproduct) {

				$countStokUD 	= $countStokUD + 1;

				foreach ($forGudangUD['data'] as $valueGudangUD) {

					if ($valueGudangUD->id == $valueStokUD->wh_id) {

						$arrayStokUD[] 		= array(

							'name_gudang' 	=> $valueGudangUD->name,

							'stok_gudang' 	=> number_format($valueStokUD->qty)

						);

					}

				}

			} else {

			}

		}

		$url = 'https://maxipro.id.test/images/barang/';

		$data['image'] = $url . $product->image;

		$data['stokproduct'] 			= $arrayStokPT;

		$data['stokproductUD'] 			= $arrayStokUD;

		$data['countStokPT'] 			= $countStokPT;

		$data['countStokUD'] 			= $countStokUD;


		$totalStokGudang = 0; // Inisialisasi variabel untuk menyimpan total stok

		// Loop melalui setiap elemen di array $arrayStokPT
		foreach ($arrayStokPT as $item) {
		    // Pastikan 'stok_gudang' ada di dalam elemen sebelum menjumlahkannya
		    if (isset($item['stok_gudang'])) {
		        $totalStokGudang += $item['stok_gudang'];
		    }
		}
		foreach ($arrayStokUD as $item) {
		    // Pastikan 'stok_gudang' ada di dalam elemen sebelum menjumlahkannya
		    if (isset($item['stok_gudang'])) {
		        $totalStokGudang += $item['stok_gudang'];
		    }
		}
		// Simpan total stok di $data['stokproduct']
		$data['sum_stok'] = $totalStokGudang;

		echo goResult(true, $data);

		return;

	}



	public function restok_tambah_post()

	{



		$teknisi_id = $this->session->userdata('teknisi_id');



		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Teknisi tidak ada'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);



		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [

			'required' 	=> [

				['tgl_request'], ['product'], ['jml_permintaan']

			]

		];

		$validate 	= Validation::check($rules, 'post');

		if (!$validate->auth) {

			echo goResult(false, $validate->msg);

			return;

		}

		$tgl_request 				= $this->input->post('tgl_request');

		$product 					= $this->input->post('product');

		$laststok 					= $this->input->post('laststok');

		$jml_permintaan 			= $this->input->post('jml_permintaan');

		$keterangan 				= $this->input->post('keterangan');

		$allstok_request = RestokModel::where('status_deleted',0)->where('status','requested')->get();
		
		$stok_same =0;

		foreach ($allstok_request as $key => $result) {
			
			if($result->id_barang == $product){
				// echo goResult(false, 'Maaf, barang sudah ada di data list restok');
				$stok_same =1;
				// return;
			}
		}

		$restok 					= new RestokModel;

		$restok->tgl_request 		= $tgl_request;
		
		$restok->id_barang 			= $product;
	
		
		$restok->id_teknisi 		= $teknisi_id;

		$restok->jml_permintaan 	= $jml_permintaan;

		$restok->last_stok 			= $laststok;

		$restok->keterangan 		= $keterangan;

		$restok->status 			= 'requested';
		if($stok_same!=1){
			$restok->save();
			$newRestokData = $restok->toArray(); // Adjust if your model uses a different method

		    // Return the result with the new restok data
		    echo json_encode([
		        'success' => true,
		        'message' => 'Restok berhasil ditambahkan',
		        'data' => [
		            'restok' => $newRestokData // Return the new restok data
		        ]
		    ]);

			return;

		} else {

			echo goResult(false, 'Maaf, barang sudah ada di data list restok');

			return;

		}

	}



	public function restok_viewedit_get($id_product)

	{

		$data['restok'] = RestokModel::where('restok.id', $id_product)

			->join('barang', 'restok.id_barang', '=', 'barang.id')

			->select('restok.*', 'barang.new_kode', 'barang.name as barang_name')

			->first();



		$product 			= BarangModel::find($data['restok']->id_barang);

		$codeproduct 		= $product->new_kode;

		//PT

		$masterItemPT 		= ApiBee::getMasterItemPT();

		$forStokPT 			= ApiBee::getStokPT();

		$forGudangPT 		= ApiBee::getGudangPT();

		$arrayStokPT 		= array();

		$countStokPT 		= 0;

		foreach ($forStokPT['data'] as $valueStokPT) {

			if ($valueStokPT->itemid == $codeproduct) {

				$countStokPT 	= $countStokPT + 1;

				foreach ($forGudangPT['data'] as $valueGudangPT) {

					if ($valueGudangPT->id == $valueStokPT->wh_id) {

						$arrayStokPT[] 		= array(

							'name_gudang' 	=> $valueGudangPT->name,

							'stok_gudang' 	=> number_format($valueStokPT->qty)

						);

					}

				}

			} else {

			}

		}

		//UD

		$formasterItemUD 	= ApiBee::getMasterItemUD();

		$forStokUD 			= ApiBee::getStokUD();

		$forGudangUD 		= ApiBee::getGudangUD();

		$arrayStokUD 		= array();

		$countStokUD 		= 0;

		foreach ($forStokUD['data'] as $valueStokUD) {

			if ($valueStokUD->itemid == $codeproduct) {

				$countStokUD 	= $countStokUD + 1;

				foreach ($forGudangUD['data'] as $valueGudangUD) {

					if ($valueGudangUD->id == $valueStokUD->wh_id) {

						$arrayStokUD[] 		= array(

							'name_gudang' 	=> $valueGudangUD->name,

							'stok_gudang' 	=> number_format($valueStokUD->qty)

						);

					}

				}

			} else {

			}

		}

		$url = 'https://maxipro.id.test/images/barang/';

		$data['image'] = $url . $product->image;

		$this->db->select('id, name, new_kode');



		$this->db->from('barang');



		$this->db->where('status_deleted', '0');



		$this->db->order_by('name', 'asc');



		$query = $this->db->get();



		$data['product'] = $query->result_array();

		$data['stokproduct'] 			= $arrayStokPT;

		$data['stokproductUD'] 			= $arrayStokUD;

		$data['countStokPT'] 			= $countStokPT;

		$data['countStokUD'] 			= $countStokUD;

		echo goResult(true, $data);

		return;

	}



	public function restok_edit_post()

	{



		$teknisi_id = $this->session->userdata('teknisi_id');



		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Teknisi tidak ada'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);



		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [

			'required' 	=> [

				['tgl_request'], ['product'], ['jml_permintaan']

			]

		];

		$validate 	= Validation::check($rules, 'post');

		if (!$validate->auth) {

			echo goResult(false, $validate->msg);

			return;

		}

		$id = $this->input->post('id');

		$restok 					= RestokModel::find($id);

		if (!$restok) {

			echo goResult(false, "Restok tidak ada");

			return;

		}

		$detailCommercial 			= PenjualanfromchinadetailModel::where('id_restok', $id)->desc()->get();

		if ($restok->status == 'reject' && count($detailCommercial) > 0) {

			echo goResult(false, "Maaf, item sudah di proses di commercial invoice");

			return;

		}

		$tgl_request 				= $this->input->post('tgl_request');

		$product 					= $this->input->post('product');

		$laststok 					= $this->input->post('laststok');

		$jml_permintaan 			= $this->input->post('jml_permintaan');

		$keterangan 				= $this->input->post('keterangan');

		$restok->tgl_request 		= $tgl_request;

		$restok->id_barang 			= $product;

		$restok->id_teknisi 		= $teknisi_id;

		$restok->jml_permintaan 	= $jml_permintaan;

		$restok->last_stok 			= $laststok;

		$restok->keterangan 		= $keterangan;

		$restok->status 			= 'requested';

		if ($restok->save()) {

			echo goResult(true, 'Restok berhasil di edit');

			return;

		} else {

			echo goResult(false, 'Restok gagal di edit');

			return;

		}

	}



	public function restok_hapus_delete($id)

	{

		$restok 						= RestokModel::find($id);

		if (!$restok) {

			echo goResult(false, 'Maaf, restok tidak ada');

			return;

		}

		if ($restok->status != 'requested') {

			echo goResult(false, 'Maaf, item sudah di proses di commercial invoice');

			return;

		}

		$restok->status_deleted 		= '1';

		$restok->save();

		echo goResult(true, 'Data anda berhasil dihapus');

		return;

	}



	//api module pembeliaan fitur order_pembelian

	public function order_pembelian_get()

	{
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }

		$requested_val = $this->input->get('requested_check') ?: 'requested';



		$checkdatevalue = $this->input->get('checkdatevalue') ?: 'unchecked';



		if ($checkdatevalue === 'unchecked') {

			$startDate = RestokModel::orderBy('tgl_request', 'asc')->value('tgl_request');

		} else {

			$startDate = $this->input->get('tgl_awal') ?: date('Y-m-d', strtotime('-7 days'));

		}



		$tgl_akhir = $this->input->get('tgl_akhir') ?: date('Y-m-d');

		$kode_barang = $this->input->get('kode_barang');

		$nama_barang = $this->input->get('nama_barang');

		$jenis = $this->input->get('jenis') ?: 'all';

		$supplier = $this->input->get('supplierfilter') ?: 'all';



		if (!$tgl_akhir) {

			$lastDate = date('Y-m-d');

		} else {

			$lastDate = $tgl_akhir ?: date('Y-m-d');

		}



		$valuesupplier = $supplier ?: 'all';



		$status_arr = [

			$requested_val

		];



		$restokQuery = RestokModel::select('id_barang', 'tgl_request')

			->where('status_deleted', '0');



		if ($checkdatevalue == 'checked') {

			$restokQuery->whereDate('tgl_request', '>=', $startDate)

				->whereDate('tgl_request', '<=', $lastDate);

		}



		if (!empty($status_arr)) {

			$restokQuery->whereIn('status', $status_arr);

		}



		$restok = $restokQuery->groupBy('id_barang')

			->orderBy('tgl_request', 'desc')

			->get();



		$idProduct = $restok->pluck('id_barang')->toArray();



		$productQuery = BarangModel::whereIn('id', $idProduct);



		if ($nama_barang) {

			$productQuery->where('name', 'LIKE', '%' . $nama_barang . '%');

		}



		if ($kode_barang) {

			$productQuery->where('new_kode', $kode_barang);

		}



		$product = $productQuery->get();

		$productjenis = $product->pluck('id')->toArray();



		$productbyjenisQuery = BarangModel::whereIn('id', $productjenis);



		if ($jenis !== 'all') {

			$productbyjenisQuery->where('id_category', $jenis);

		}



		$productbyjenis = $productbyjenisQuery->get();

		$fixproduct = $productbyjenis->pluck('id')->toArray();

		if ($requested_val == 'all') {



			$orderQuery = RestokModel::select('barang.new_kode as new_kode','barang.id as barang_id','barang.name as nama_barang', 'barang.name_english as nama_barang_english','barang.name_china as nama_barang_china','barang.image as image','teknisi.id as teknisi_id','teknisi.name as name_teknisi','barang.cbm as kubik','supplier.name as name_supplier','supplier.id as supplier_id','restok.id', 'restok.id_barang', 'restok.id_teknisi', 'restok.id_supplier', 'restok.jml_permintaan', 'restok.tgl_request', 'restok.last_stok', 'restok.keterangan', 'restok.category', 'restok.status')

				->join('supplier', 'restok.id_supplier', '=', 'supplier.id')

				->join('barang', 'restok.id_barang', '=', 'barang.id')

				->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')

				->where('restok.status_deleted', '0')

				->orderBy('restok.tgl_request', 'desc');



			if ($checkdatevalue == 'checked') {

				$orderQuery->whereBetween('tgl_request', [$startDate, $lastDate]);

			}



			if ($supplier !== 'all') {

				$orderQuery->where('id_supplier', $supplier);

			}



			$total = $orderQuery->get();

			$data['orderpembelian'] = $orderQuery->get();

			$supplier_view		= SupplierModel::select('id', 'name','company')->where('status', '1')->orderBy('name', 'asc')->get();

			$category = BarangcategoryModel::select('id', 'name')

			->where('status', '1')

			->orderBy('id', 'asc')

			->get();

			$data = [

				'fullorderpembelian' => $total,

				'supplier' => $supplier_view,

				'category' =>$category,

				'tgl_awal' => $startDate,

				'tgl_akhir' => $lastDate,

				'kode_barang' => $kode_barang,

				'nama_barang' => $nama_barang,

				'jenis' => $jenis,

				'supplierFilter' => $valuesupplier,

				'checkdatevalue' => $checkdatevalue,

				'requested_check' => $requested_val,

			];



			$updatelaststok = $this->updateRestokLiveNotAjax();



			echo goResult(true, $data);

			return;

		} else {



			$orderQuery = RestokModel::selectRaw(
				'barang.new_kode as new_kode, 
				barang.id as barang_id, 
				barang.name as nama_barang, 
				 barang.name_english as nama_barang_english,
				 barang.name_china as nama_barang_china,
				CONCAT("https://maxipro.id/images/barang/", barang.image) as image, 
				teknisi.id as teknisi_id, 
				teknisi.name as name_teknisi, 
				barang.cbm as kubik, 
				supplier.name as name_supplier, 
				supplier.id as supplier_id, 
				restok.id, 
				restok.id_barang, 
				restok.id_teknisi, 
				restok.id_supplier, 
				restok.jml_permintaan, 
				restok.tgl_request, 
				restok.last_stok, 
				restok.keterangan, 
				restok.category, 
				restok.status'
			)

				->join('barang', 'restok.id_barang', '=', 'barang.id')

				->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')

				->join('supplier', 'restok.id_supplier', '=', 'supplier.id')

				->whereIn('restok.id_barang', $fixproduct)

				->where('restok.status_deleted', '0')

				->whereIn('restok.status', $status_arr)

				->orderBy('restok.tgl_request', 'desc');

			if ($checkdatevalue == 'checked') {

				$orderQuery->whereBetween('tgl_request', [$startDate, $lastDate]);

			}



			if ($supplier !== 'all') {

				$orderQuery->where('id_supplier', $supplier);

			}



			$total = $orderQuery->get();

			$data['orderpembelian'] = $orderQuery->get();
			
			$supplier_view		= SupplierModel::select('id', 'name','company')->where('status', '1')->orderBy('name', 'asc')->get();

				$category = BarangcategoryModel::select('id', 'name')

			->where('status', '1')

			->orderBy('id', 'asc')

			->get();

			$data = [

				'fullorderpembelian' => $total,

				'supplier' => $supplier_view,

				'category'=>$category,

				'tgl_awal' => $startDate,

				'tgl_akhir' => $lastDate,

				'kode_barang' => $kode_barang,

				'nama_barang' => $nama_barang,

				'jenis' => $jenis,

				'supplierFilter' => $valuesupplier,

				'checkdatevalue' => $checkdatevalue,

				'requested_check' => $requested_val,

			];



			$updatelaststok = $this->updateRestokLiveNotAjax();

			
			echo goResult(true, $data);

			return;

		}

	}
	public function order_pembelian_detailstok_post(){
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }

        //to penerimaan
        $filter_tahun = $this->input->post('tahun');
          $filter_tahun_akhir = $this->input->post('tahun_akhir');
        $filter_bulan = $this->input->post('bulan');
        $filter_bulan_akhir = $this->input->post('bulan_akhir');
        $filter_hari_awal = 01;
        $filter_hari_akhir = 31;
        $data_filter =array(
		    'tahun' => $filter_tahun,
		    'tahun_akhir' => $filter_tahun_akhir,
		    'bulan' => $filter_bulan,
		    'bulan_akhir' => $filter_bulan_akhir,
	
        );

        $filtered_data = array_filter($data_filter);
        if(empty($filtered_data))
        {
        	// dd('a');
        	$item_code = $this->input->post('item_code');
	    	$name = $this->input->post('name');

	    	$data['penerimaanByProduct'] = PenerimaanpembeliandetailModel::where('name',$name)->get();
	    	// dd($data['penerimaanByProduct']->toJson());
	    	$idArrayPenerimaanDetail = [];
	    	foreach($data['penerimaanByProduct'] as $penerimaan_detail){
	    		$idArrayPenerimaanDetail[] = $penerimaan_detail->id_penerimaanpembelian;
	    	}
	    	// dd($idArrayPenerimaanDetail);

	    	$data['penerimaan'] = PenerimaanpembelianModel::whereIn('id',$idArrayPenerimaanDetail)->where('tgl_terima','>=','2024-01-01')->where('status',1)->get();
	    	// dd($data['penerimaan']->toJson());
	    	$idArrayPenerimaan = [];
	    	$Array_TglPenerimaan = [];
	    	foreach($data['penerimaan'] as $penerimaan){
	    		$idArrayPenerimaan[] = $penerimaan->id;
	    		$Array_TglPenerimaan[] = $penerimaan->tgl_terima;
	    	}
	    	$data_json['tgl_penerimaan'] = array_values($Array_TglPenerimaan);
	    	// dd($idArrayPenerimaan);
	    	$data_json['penerimaandetail'] = PenerimaanpembeliandetailModel::whereIn('id_penerimaanpembelian',$idArrayPenerimaan)->where('name',$name)->get();
	    		// dd($data2['penerimaandetail']->toJson());
	    	//to penjualan
	    	// Fetch sale details by product using item_code
	    	$data['saleByProduct'] = PenjualandetailModel::where('item_code', $item_code)->get();

		    $idArray = [];
		    foreach ($data['saleByProduct'] as $item) {
		        $idArray[] = $item->id_penjualan;
		    }

		    // Fetch penjualan records
		    $data['penjualan'] = PenjualanModel::whereIn('id', $idArray)->where('tgl_transaksi', '>=', '2024-01-01')->where('status_deleted', 0) ->where('status',1)->get();
			$data_json['thn_transaksi'] = PenjualanModel::selectRaw('YEAR(tgl_transaksi) as tahun')
                    ->orderBy('tgl_transaksi', 'ASC')
                    ->first()
                    ->tahun;

			$data_json['thn_transaksi_terakhir'] = PenjualanModel::selectRaw('YEAR(tgl_transaksi) as tahun')->orderBy('tgl_transaksi', 'DESC')
                        ->first()->tahun;

		    $idArrayPenjualan = [];
		    $customerArrayPenjualan = [];
		    $tglArrayPenjualan = [];

			// Loop untuk mengisi array ID penjualan dan mitrabisnis
		    foreach ($data['penjualan'] as $result) {
		    	$idArrayPenjualan[] = $result->id;
		    	$customerArrayPenjualan[$result->id] = $result->name_mitrabisnis;
		    	$tglArrayPenjualan[$result->id] = $result->tgl_transaksi;
		    }
		    $data_json['tgl'] = array_values($tglArrayPenjualan);
		    $data_json['mitrabisnis'] = array_values($customerArrayPenjualan);

			// Mendapatkan detail produk
		    $detailProducts = PenjualandetailModel::whereIn('id_penjualan', $idArrayPenjualan)
		    ->where('name', $name)
		    ->get();

			// Menambahkan mitrabisnis ke detail produk dan menghitung totalQty
				    $totalQty = 0;
			foreach ($detailProducts as $product) {
				    	$product->mitrabisnis = $customerArrayPenjualan[$product->id_penjualan];
			    $totalQty += $product->qty; // Menghitung totalQty
			}

			// Memasukkan detail produk dan totalQty ke dalam data2
			$data_json['detailProduct'] = $detailProducts;
			$data_json['totalQty'] = $totalQty;

			echo goResult(true,$data_json);
			return;
        }
        else{
        	// dd('a');
        	$item_code = $this->input->post('item_code');
	    	$name = $this->input->post('name');

	    	$data['penerimaanByProduct'] = PenerimaanpembeliandetailModel::where('name',$name)->get();
	    	// dd($data['penerimaanByProduct']->toJson());
	    	$idArrayPenerimaanDetail = [];
	    	foreach($data['penerimaanByProduct'] as $penerimaan_detail){
	    		$idArrayPenerimaanDetail[] = $penerimaan_detail->id_penerimaanpembelian;
	    	}
	    	
	    	$startDate = "$filter_tahun-$filter_bulan-$filter_hari_awal";
	    	$endDate = "$filter_tahun_akhir-$filter_bulan_akhir-$filter_hari_akhir";
	    	
	    	$data['penerimaan'] = PenerimaanpembelianModel::whereIn('id', $idArrayPenerimaanDetail)
	    	   ->where('tgl_terima', '>=', $startDate)
   			 ->where('tgl_terima', '<=', $endDate)
	    	->where('status', 1)
	    	->get();
	    	// dd($data['penerimaan']->toJson());
	    	
	    	$idArrayPenerimaan = [];
	    	$Array_TglPenerimaan = [];
	    	foreach($data['penerimaan'] as $penerimaan){
	    		$idArrayPenerimaan[] = $penerimaan->id;
	    		$Array_TglPenerimaan[] = $penerimaan->tgl_terima;
	    	}
	    	$data_json['tgl_penerimaan'] = array_values($Array_TglPenerimaan);
	    	
	    	$data_json['penerimaandetail'] = PenerimaanpembeliandetailModel::whereIn('id_penerimaanpembelian',$idArrayPenerimaan)->where('name',$name)->get();
	    		
	    	// Fetch sale details by product using item_code
	    	$data['saleByProduct'] = PenjualandetailModel::where('item_code', $item_code)->get();

		    $idArray = [];
		    foreach ($data['saleByProduct'] as $item) {
		        $idArray[] = $item->id_penjualan;
		    }

		    // Fetch penjualan records
		    $data['penjualan'] = PenjualanModel::whereIn('id', $idArray)
		     ->where('tgl_transaksi', '>=', $startDate)
   			 ->where('tgl_transaksi', '<=', $endDate)
		    ->where('status_deleted', 0)
		    ->where('status',1)
		    ->get();
			$data_json['thn_transaksi'] = PenjualanModel::selectRaw('YEAR(tgl_transaksi) as tahun')
                    ->orderBy('tgl_transaksi', 'ASC')
                    ->first()
                    ->tahun;

			$data_json['thn_transaksi_terakhir'] = PenjualanModel::selectRaw('YEAR(tgl_transaksi) as tahun')->orderBy('tgl_transaksi', 'DESC')
                        ->first()->tahun;

		    $idArrayPenjualan = [];
		    $customerArrayPenjualan = [];
		    $tglArrayPenjualan = [];

			// Loop untuk mengisi array ID penjualan dan mitrabisnis
		    foreach ($data['penjualan'] as $result) {
		    	$idArrayPenjualan[] = $result->id;
		    	$customerArrayPenjualan[$result->id] = $result->name_mitrabisnis;
		    	$tglArrayPenjualan[$result->id] = $result->tgl_transaksi;
		    }
		    // dd($idArrayPenjualan);
		    $data_json['tgl'] = array_values($tglArrayPenjualan);
		    $data_json['mitrabisnis'] = array_values($customerArrayPenjualan);

			// Mendapatkan detail produk
		    $detailProducts = PenjualandetailModel::whereIn('id_penjualan', $idArrayPenjualan)
		    ->where('name', $name)
		    ->get();

			// Menambahkan mitrabisnis ke detail produk dan menghitung totalQty
				    $totalQty = 0;
			foreach ($detailProducts as $product) {
				    	$product->mitrabisnis = $customerArrayPenjualan[$product->id_penjualan];
			    $totalQty += $product->qty; // Menghitung totalQty
			}

			// Memasukkan detail produk dan totalQty ke dalam data2
			$data_json['detailProduct'] = $detailProducts;
			  // dd($data_json['detailProduct']->toJson());
			$data_json['totalQty'] = $totalQty;

			echo goResult(true,$data_json);
			return;
        }
	        
	}



	public function order_pembelian_viewedit_get($id)

	{

		$data['supplier'] 			= SupplierModel::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();

		$data['restok'] 			= RestokModel::find($id);



		echo goResult(true, $data);

		return;

	}



	public function order_pembelian2_get()

	{

		$tgl_awal 						= $this->input->get('tgl_awal');

		$tgl_akhir 						= $this->input->get('tgl_akhir');

		$kode_barang 					= $this->input->get('kode_barang');

		$nama_barang 					= $this->input->get('nama_barang');

		$jenis 							= $this->input->get('jenis');

		$supplier 						= $this->input->get('supplierfilter');

		$checkdatevalue 				= $this->input->get('checkdatevalue');



		//status check

		$requested_check 				= $this->input->get('requested_check');

		$process_check 					= $this->input->get('process_check');

		$completed_check 				= $this->input->get('completed_check');

		$reject_check 					= $this->input->get('reject_check');



		if (!isset($requested_check)) {

			$requested_val = 'requested';

		} else {

			$requested_val = $requested_check;

		}



		if (!$process_check) {

			$process_val = '';

		} else {

			$process_val = $process_check;

		}



		if (!$completed_check) {

			$completed_val = '';

		} else {

			$completed_val = $completed_check;

		}



		if (!$reject_check) {

			$reject_val = '';

		} else {

			$reject_val = $reject_check;

		}



		if (!$checkdatevalue) {

			$checkdatefix = 'unchecked';

		} else {

			$checkdatefix = $checkdatevalue;

		}



		if (!$tgl_akhir) {

			$lastDate 	= date('Y-m-d');

		} else {

			if ($tgl_akhir == '') {

				$lastDate 	= date('Y-m-d');

			} else {

				$lastDate 	= $tgl_akhir;

			}

		}



		if (!$tgl_awal) {

			$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

		} else {

			if ($tgl_awal == '') {

				$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

			} else {

				$startDate 	= $tgl_awal;

			}

		}



		if (!$supplier) {

			$valuesupplier = 'all';

		} else {

			$valuesupplier = $supplier;

		}



		$status_arr = [$requested_val, $process_val, $completed_val, $reject_val];



		if ($checkdatefix == 'checked') {

			$restok 		= RestokModel::where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->whereIn('status', $status_arr)->groupBy('id_barang')->orderBy('tgl_request', 'desc')->get();

		} else {

			$restok 		= RestokModel::where('status_deleted', '0')->whereIn('status', $status_arr)->groupBy('id_barang')->orderBy('tgl_request', 'desc')->get();

		}



		$idProduct 			= array();

		foreach ($restok as $key => $value) {

			$idProduct[] 	= $value->id_barang;

		}



		if (!$nama_barang && !$kode_barang) {

			$kodeBarang 		= '';

			$namaBarang 		= '';

			$product 			= BarangModel::whereIn('id', $idProduct)->get();

		} elseif ($nama_barang && !$kode_barang) {

			$kodeBarang 		= '';

			$namaBarang 		= $nama_barang;

			$product 			= BarangModel::whereIn('id', $idProduct)->where('name', 'LIKE', '%' . $nama_barang . '%')->get();

		} elseif (!$nama_barang && $kode_barang) {

			$kodeBarang 		= $kode_barang;

			$namaBarang 		= '';

			$product 			= BarangModel::whereIn('id', $idProduct)->where('new_kode', $kode_barang)->get();

		} else {

			$kodeBarang 		= $kode_barang;

			$namaBarang 		= $nama_barang;

			$product 			= BarangModel::whereIn('id', $idProduct)->where('name', 'LIKE', '%' . $nama_barang . '%')->where('new_kode', $kode_barang)->get();

		}



		$productjenis 			= array();

		foreach ($product as $key => $value) {

			$productjenis[] 	= $value->id;

		}



		if (!$jenis) {

			$jenisValue 		= 'all';

			$productbyjenis 	= BarangModel::whereIn('id', $productjenis)->get();

		} else {

			if ($jenis == 'all') {

				$jenisValue 	= 'all';

				$productbyjenis = BarangModel::whereIn('id', $productjenis)->get();

			} else {

				$jenisValue 	= $jenis;

				$productbyjenis = BarangModel::whereIn('id', $productjenis)->where('id_category', $jenis)->get();

			}

		}



		$fixproduct 			= array();

		foreach ($productbyjenis as $key => $value) {

			$fixproduct[] 		= $value->id;

		}



		$page 						= $this->uri->segment(5);

		if (!is_numeric($page)) {

			$page 					= 0;

		}



		$paginate					= new Myweb_pagination;

		if ($checkdatefix == 'checked') {

			if ($valuesupplier != 'all') {

				$total						= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('id_supplier', $valuesupplier)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->orderBy('tgl_request', 'desc')->get();

				$data['orderpembelian'] 	= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('id_supplier', $valuesupplier)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->orderBy('tgl_request', 'desc')->get();

			} else {

				$total						= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->orderBy('tgl_request', 'desc')->get();

				$data['orderpembelian'] 	= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('status_deleted', '0')->whereDate('tgl_request', '>=', $startDate)->whereDate('tgl_request', '<=', $lastDate)->orderBy('tgl_request', 'desc')->get();

			}

		} else {

			if ($valuesupplier != 'all') {

				$total						= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('id_supplier', $valuesupplier)->where('status_deleted', '0')->orderBy('tgl_request', 'desc')->get();

				$data['orderpembelian'] 	= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('id_supplier', $valuesupplier)->where('status_deleted', '0')->orderBy('tgl_request', 'desc')->get();

			} else {

				$total						= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('status_deleted', '0')->orderBy('tgl_request', 'desc')->get();

				$data['orderpembelian'] 	= RestokModel::whereIn('id_barang', $fixproduct)->whereIn('status', $status_arr)->where('status_deleted', '0')->orderBy('tgl_request', 'desc')->get();

			}

		}



		$data['numberpage'] 		= $page * 20;

		$data['fullorderpembelian'] = $total;



		$data['tgl_awal'] 			= $startDate;

		$data['tgl_akhir'] 			= $lastDate;

		$data['kode_barang'] 		= $kodeBarang;

		$data['nama_barang'] 		= $namaBarang;

		$data['jenis'] 				= $jenisValue;

		$data['supplierFilter'] 	= $valuesupplier;

		$data['checkdatevalue'] 	= $checkdatefix;

		$updatelaststok 			= $this->updateRestokLiveNotAjax();



		//status check

		$data['requested_check'] 	= $requested_val;

		$data['process_check'] 		= $process_val;

		$data['completed_check'] 	= $completed_val;

		$data['reject_check'] 		= $reject_val;



		echo goResult(true, $data);

		return;

	}



	public function order_pembelian_edit_post(){

			$rules = [

				'required' 	=> [

					['jml_permintaan'],['supplier']

				]

			];



			$validate 	= Validation::check($rules,'post');

			if(!$validate->auth){

				echo goResult(false,$validate->msg);

				return;

			}

			$id = $this->input->post('id');

			$restok 					= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, "Order Pembelian tidak ada");

				return;

			}



			$detailCommercial 			= PenjualanfromchinadetailModel::where('id_restok', $id)->desc()->get();

			if($restok->status == 'reject' && count($detailCommercial) > 0){

				echo goResult(false, "Maaf, item sudah di proses di commercial invoice");

				return;

			}



			$jml_permintaan 			= $this->input->post('jml_permintaan');

			$keterangan 				= $this->input->post('keterangan');

			$supplier 					= $this->input->post('supplier');

			if($restok->status == 'reject'){

				$restok->tgl_request 	= date('Y-m-d');

			}



			$restok->jml_permintaan 	= $jml_permintaan;

			$restok->keterangan 		= $keterangan;

			$restok->id_supplier 		= $supplier;

			$restok->status 			= 'requested';

			if($restok->save()){

				echo goResult(true, 'Order Pembelian berhasil di edit');

				return;

			}else{

				echo goResult(false, 'Order Pembelian gagal di edit');

				return;

			}

	}



	public function order_pembelian_rejected_get($id){

		$restok 				= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, 'Maaf, restok / order pembelian tidak ada');

				return;

			}

			

			$restok->status 		= 'reject';

			$restok->save();



			echo goResult(true, 'Data anda berhasil direject');

			return;

	}



	public function order_pembelian_undo_get($id){

		$restok 				= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, 'Maaf, restok / order pembelian tidak ada');

				return;

			}



			$restok->status 		= 'requested';

			$restok->status_lcl 	= '0';

			$restok->save();



			echo goResult(true, 'Data anda berhasil diundo');

			return;

	}



	public function order_pembelian_hapus_delete($id){

		$restok 				= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, 'Maaf, restok / order pembelian tidak ada');

				return;

			}



			if($restok->status != 'requested'){

				echo goResult(false, 'Maaf, item sudah di proses di commercial invoice');

				return;

			}



			$restok->status_deleted = '1';

			$restok->save();



			echo goResult(true, 'Data anda berhasil dihapus');

			return;

	}



	public function order_pembelian_select_supplier_post(){

			$rules 		= [

				'required' 	=> [

					['kode']

				]

			];



			$validate 	= Validation::check($rules,'post');

			if(!$validate->auth){

				echo goResult(false,$validate->msg);

				return;

			}

			$id = $this->input->post('id');

			$kode = $this->input->post('kode');

			// $explodekode 					= explode('-', $this->input->post('kode'));

			// $id 							= $explodekode[0];

			$restok 						= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, 'Restok tidak ada');

				return;

			}



				$restok->id_supplier 			= $kode;

			if($restok->save()){

				echo goResult(true, 'success');

				return;

			}else{

				echo goResult(false, 'Supplier error');

				return;	

			}

	}

	public function order_pembelian_category_post(){

			$rules 		= [

				'required' 	=> [

					['category']

				]

			];



			$validate 	= Validation::check($rules,'post');

			if(!$validate->auth){

				echo goResult(false,$validate->msg);

				return;

			}

			$id = $this->input->post('id');

			$category = $this->input->post('category');

			// $explodekode 					= explode('-', $this->input->post('kode'));

			// $id 							= $explodekode[0];

			$restok 						= RestokModel::find($id);

			if(!$restok){

				echo goResult(false, 'Restok tidak ada');

				return;

			}



				$restok->category 			= $category;

			if($restok->save()){

				echo goResult(true, 'Succes update category');

				return;

			}else{

				echo goResult(false, 'Supplier error');

				return;	

			}

	}



	public function comercial_invoice_get(){

			$teknisi_id = $this->session->userdata('teknisi_id');



			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST

			if (!$teknisi_id) {



				$teknisi_id = $this->input->post('teknisi_id');

			}







			// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error

			if (!$teknisi_id) {



				$this->response([



					'success' => false,



					'message' => 'Teknisi tidak ada'



				], 401);



				return;

			}



			$teknisi = TeknisiModel::select('teknisi.*', 'position_karyawan.name as name_position')

			->join('position_karyawan', 'teknisi.id_position', '=', 'position_karyawan.id')

			->where('teknisi.id', $teknisi_id)

			->first();

			

			

			// Periksa apakah teknisi ditemukan

			if (!$teknisi) {



				// Teknisi tidak ditemukan, kirim respons error



				$this->response([



					'success' => false,



					'message' => 'Anda Belum Login'



				], 401);



				return;

			}



			if ($teknisi->name_position !== 'Manager' && $teknisi->name_position !== 'Web Developer') {



				// kondisi diatas akan dijalankan apabaila posisi selain web developer dan manager



				$this->response([



					'success' => false,



					'message' => 'Position tidak sesuai'



				], 401);



				return;

			}

			$tgl_awal 				= $this->input->get('tgl_awal');

			$tgl_akhir 				= $this->input->get('tgl_akhir');

			$nameFilter 			= $this->input->get('name');

			$checkdatevalue 		= $this->input->get('checkdatevalue');

			$invoice 				= $this->input->get('invoice');
			
			$supplier 				= $this->input->get('supplier');

			//status check

			$requested_check 				= $this->input->get('requested_check');
			

			if(!isset($requested_check)){

				$requested_val = 'requested';

			}else{

				$requested_val = $requested_check;

			}



			if(!$checkdatevalue){

				$checkdatefix 	= 'unchecked';

				$startDate = PenjualanfromchinaModel::orderBy('date', 'asc')->value('date');

			}else{

				$checkdatefix 	= $checkdatevalue;

			}



			if(!$tgl_akhir){

				$lastDate 		= date('Y-m-d');

			}else{

				if($tgl_akhir == ''){

					$lastDate 	= date('Y-m-d');

				}else{

					$lastDate 	= $tgl_akhir;

				}

			}



			if(!$tgl_awal){

				

					$startDate = PenjualanfromchinaModel::orderBy('date', 'asc')->value('date');

			}else{

				if($tgl_awal == ''){

					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));

				}else{

					$startDate 	= $tgl_awal;

				}

			}



			if(!$nameFilter){

				$valuename 				= '';

			}else{

				$valuename 				= $nameFilter;

			}



			$status_arr = [$requested_val];

			// dd($status_arr);

			if($checkdatefix == 'checked'){

				//kondisi bila tidak ada invoice dan supplier maka by date

				if($status_arr[0]=='all'){
					$penjualanQuery = PenjualanfromchinaModel::whereDate('date', '>=', $startDate)

					->whereDate('date', '<=', $lastDate)

					->where('name', 'LIKE', '%'.$valuename.'%')

					->where('status_deleted', '0');

				

				}
				else{
					$penjualanQuery = PenjualanfromchinaModel::whereDate('date', '>=', $startDate)

					->whereDate('date', '<=', $lastDate)

					->where('name', 'LIKE', '%'.$valuename.'%')

					->where('status_deleted', '0')

					->whereIn('status', $status_arr);

				}				


				//kondisi bila supplier ada maka filter by supplier dan date

				if ($supplier) {

					$penjualanQuery->where('id_supplier', $supplier);

				}

				//kondisi bila invoice ada maka filter by invoice dan date

				if ($invoice) {

					$penjualanQuery->where('invoice_no', 'LIKE', '%'.$invoice.'%');

				}



				$penjualan = $penjualanQuery->orderBy('date', 'desc')->get();

				

			}else{

				if($status_arr[0]=='all'){

					$penjualan 				= PenjualanfromchinaModel::where('name', 'LIKE', '%'.$valuename.'%')->where('status_deleted', '0')->orderBy('date', 'desc')->get();
				}
				else{
					$penjualan 				= PenjualanfromchinaModel::where('name', 'LIKE', '%'.$valuename.'%')->where('status_deleted', '0')->whereIn('status', $status_arr)->orderBy('date', 'desc')->get();
				}
			}



			

			$idPenjualan 				= array();

			foreach ($penjualan as $key => $value) {

				$idPenjualan[] 			= $value->id;

			}



			

			$total						= count($idPenjualan);

		

			$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang','fcl','lcl'])

			->whereIn('id', $idPenjualan)

			->orderBy('date', 'desc');

			$totalRowsPenjualan = $penjualanQuery->count();

			$data['penjualan'] = $penjualanQuery->get();
			// $id_penjualan =[];
			// foreach ($data['penjualan'] as $result) {
			// 	$id_penjualan[] = $result->id;
			// }
			
			// $lcl_pembelian_detail = PembelianlcldetailModel::whereIn('id_penjualanfromchina',$id_penjualan)->get();
			
			// $lcl_pembelian_detail_id_pembelianlcl = [];
			// foreach($lcl_pembelian_detail as $resutl_lcl_id){

			// 	$lcl_pembelian_detail_id_pembelianlcl[] = $resutl_lcl_id->id_pembelianlcl;
			// }
			// $lcl_pembelian = PembelianlclModel::whereIn('id',$lcl_pembelian_detail_id_pembelianlcl)->get();

			// $fcl_pembelian_detail = FclContainerdetailModel::whereIn('id_penjualanfromchina',$id_penjualan)->get();
			// $fcl_pembelian_detail_id_fclcontainer = [];
			// foreach ($fcl_pembelian_detail as $result_fcl_id) {
			// 	$fcl_pembelian_detail_id_fclcontainer[] = $result_fcl_id->id_fclcontainer;
			// }
			// $fcl_pembelian = FclContainerModel::whereIn('id',$fcl_pembelian_detail_id_fclcontainer)->get();
			
			$data['total_rows_penjualan'] = $totalRowsPenjualan;

			
			$data['matauang'] = MatauangModel::where('status','1')->orderBy('id','asc')->get();




			

			$data['tgl_awal'] 			= $startDate;

			$data['tgl_akhir'] 			= $lastDate;

			$data['checkdatevalue'] 	= $checkdatefix;

			$data['nameFilter'] 		= $valuename;

			$listorder 				= RestokModel::where('status_deleted', '0')->where('id_supplier', '!=', 0)->where('status', 'requested')->groupBy('id_supplier')->get();

			$idsupplier 			= array();
			foreach ($listorder as $key => $value) {
				$idsupplier[] 		= $value->id_supplier;
			}

			$this->db->select('restok.*, barang.*, supplier.id as supplier_id, supplier.address as supplier_address, supplier.name as supplier_name, supplier.company as supplier_company, supplier.telp as supplier_telp, supplier.city as supplier_city, restok.id as restok_id, restok.status as restok_status');
			$this->db->from('restok');
			$this->db->join('barang', 'barang.id = restok.id_barang');
			$this->db->join('supplier', 'supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			$this->db->where('supplier.name !=', '-'); // Updated line to exclude supplier name '-'
			$this->db->where('restok.status', 'requested');
			$this->db->order_by('restok.id', 'DESC');
			$total_rows = $this->db->count_all_results('', false);
			$query = $this->db->get();
			$data['listorder'] = $query->result();


			$data['total_rows'] = $total_rows;
			
			$grouped_data = [];
			$row_counts = []; // New array to hold row counts
			$tot_count=0;


			$this->db->select('barang.id as barang_id, barang.new_kode as new_kode, barang.name as barang_name, barang.image as barang_image, restok.id as restok_id, restok.jml_permintaan as jml_perminataan,supplier.id as supplier_id, supplier.address as supplier_address, supplier.name as supplier_name, supplier.company as supplier_company,supplier.telp as supplier_telp, supplier.city as supplier_city,restok.id as restok_id,restok.status as restok_status'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			
			
			$this->db->where('restok.status', 'requested');
			$this->db->order_by('restok.id', 'DESC'); 
			
			$query = $this->db->get();
			$data2['listorder'] = $query->result(); 
			

			foreach ($data2['listorder'] as $row) {
				$supplier_name = $row->supplier_name;
			
				if (!isset($grouped_data[$supplier_name])) {
					$grouped_data[$supplier_name] = [];
			
				}
				$grouped_data[$supplier_name][] = $row;
				

			}
			foreach ($grouped_data as $supplier_name => $rows) {
		      
				$tot_count++;

				
		    }

			// Assign the grouped data to the data array
			$data['grouped_by_supplier'] = $grouped_data;
			$data['row_counts_supplier'] = $tot_count;

			$data['supplier'] 		= SupplierModel::whereIn('id', $idsupplier)->where('status', '1')->orderBy('name', 'asc')->get();



			$data['directory_gambar'] = 'https://maxipro.id/images/barang/';

			//status check

			$data['requested_check'] 	= $requested_val;

			$this->db->select('master_rmbtousd.*'); 
			$this->db->from('master_rmbtousd'); 
			
			

			$master_rmbtousd= $this->db->get();

			$data['masterrmbtousd'] = $master_rmbtousd->result();
					$data['matauang'] = MatauangModel::where('status',1)->get();
			$data['termin'] = TerminModel::where('status',1)->get();
			$data['account'] = AccountModel::where('status',1)->get();
		
			// $orderQuery = RestokModel::select('barang.new_kode as new_kode','barang.id as barang_id','barang.name as nama_barang', 'barang.image as image','teknisi.id as teknisi_id','teknisi.name as name_teknisi','barang.cbm as kubik','supplier.name as name_supplier','supplier.id as supplier_id','restok.id', 'restok.id_barang', 'restok.id_teknisi', 'restok.id_supplier', 'restok.jml_permintaan', 'restok.tgl_request', 'restok.last_stok', 'restok.keterangan', 'restok.category', 'restok.status')

			// ->join('barang', 'restok.id_barang', '=', 'barang.id')

			// ->join('teknisi', 'restok.id_teknisi', '=', 'teknisi.id')

			// ->join('supplier', 'restok.id_supplier', '=', 'supplier.id')


			// ->where('restok.status_deleted', '0')

			// ->whereIn('restok.status', 'requested')

			// ->orderBy('restok.tgl_request', 'desc');


			// $this->db->select('restok.id,restok.id_barang,restok.id_supplier,restok.jml_permintaan,restok.tgl_request,restok.keterangan, supplier.name as name_supplier')
			// ->from('restok')
			// ->join('supplier', 'restok.id_supplier = supplier.id')
			// ->where('restok.status_deleted', '0')
			// ->where('restok.status', 'requested')
			// ->order_by('restok.tgl_request', 'desc');
			// $query = $this->db->get();
			// $data['orderpembelianComercialInvoice'] = $query->result();

			echo goResult(true,$data);

			return;

	}
	public function comercialInvoice_supplier_get(){

			$teknisi_id = $this->session->userdata('teknisi_id');



			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST

			if (!$teknisi_id) {



				$teknisi_id = $this->input->post('teknisi_id');

			}







			// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error

			if (!$teknisi_id) {



				$this->response([



					'success' => false,



					'message' => 'Teknisi tidak ada'



				], 401);



				return;

			}
			$data['matauang'] = MatauangModel::where('status',1)->get();
			$data['termin'] = TerminModel::where('status',1)->get();
			$data['account'] =  AccountModel::where('status', 1)
                       ->orderByRaw("CASE WHEN name = 'Rekening PT' THEN 0 ELSE 1 END")
                       ->orderBy('name', 'asc')
                       ->get();
			
			$data['new_supplier'] = SupplierModel::where('status',1)->orderBy('name','asc')->get();
			$this->db->select('id, name, new_kode');

			$this->db->from('barang');

			$this->db->where('status_deleted', '0');

			$this->db->order_by('name', 'asc');

			$query = $this->db->get();

			$product = $query->result_array();
				
			$data['product'] = $product;

			$data['cabang'] = CabangModel::where('status',1)->get();

			echo goResult(true,$data);

			return;
	}
	public function comercialInvoice_filtersupplier_get($id){
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }
		$data['new_supplier'] = SupplierModel::where('status', 1)
		    ->where('id', $id)
		    ->orderBy('name', 'asc')
		    ->first(); // Mengambil satu hasil

		// Pastikan $data['new_supplier'] tidak null sebelum mengakses id
		$data['bank_supplier'] = $data['new_supplier'] 
		    ? SupplierbankModel::where('id_supplier', $data['new_supplier']->id)->get() 
		    : collect(); // Kembalikan koleksi kosong jika new_supplier null

		echo goResult(true,$data);
		return;
	}
	public function comercial_invoice_select_category_post(){
		$id = $this->input->post('id_commercial');
		$select_category = $this->input->post('selected_value');

		$penjualan = PenjualanfromchinaModel::find($id);
		$penjualan->id_supplier = $penjualan->id_supplier;
		$penjualan->name_db = $penjualan->name_db;
		$penjualan->invoice_no = $penjualan->invoice_no;
		$penjualan->packing_no = $penjualan->packing_no;
		$penjualan->contract_no = $penjualan->contract_no;
		$penjualan->name = $penjualan->name;
		$penjualan->address = $penjualan->address;
		$penjualan->city =$penjualan->city;
		$penjualan->phone = $penjualan->phone;
		$penjualan->date = $penjualan->date;
		$penjualan->incoterms = $penjualan->incoterms;
		$penjualan->location = $penjualan->location;
		$penjualan->term_of_payment = $penjualan->term_of_payment;
		$penjualan->delivery_date = $penjualan->delivery_date;
		$penjualan->package = $penjualan->package;
		$penjualan->guarantee = $penjualan->guarantee;
		$penjualan->currency = $penjualan->currency;
		$penjualan->id_supplierbank = $penjualan->id_supplierbank;
		$penjualan->id_matauang =$penjualan->id_matauang;
		$penjualan->bank_name =$penjualan->bank_name;
		$penjualan->bank_address = $penjualan->bank_address;
		$penjualan->swift_code = $penjualan->swift_code;
		$penjualan->account_no = $penjualan->account_no;
		$penjualan->beneficiary_name = $penjualan->beneficiary_name;
		$penjualan->beneficiary_address = $penjualan->beneficiary_address;
		$penjualan->mode_admin = $penjualan->mode_admin;
		$penjualan->freight_cost = $penjualan->freight_cost;
		$penjualan->insurance = $penjualan->insurance;
		$penjualan->category = $penjualan->category;
		$penjualan->status =$penjualan->status;
		$penjualan->status_terima = $penjualan->status_terima;
		$penjualan->status_deleted = $penjualan->status_deleted;
		$penjualan->created_at = $penjualan->created_at;
		$penjualan->updated_at = $penjualan->updated_at;
		$penjualan->category_comercial_invoice = $select_category;

		// dd(json_encode($penjualan->id_supplier),json_encode($penjualan));
		if($penjualan->save()){
			$data['auth'] 								= true;
			$data['msg'] 								= 'Pilih Kategori Sukses';

			echo toJson($data);
			return;	
		}else{
			$data['auth'] 								= false;
			$data['msg'] 								= 'Pilih Kategori Gagal';

			echo toJson($data);
			return;	
		}
		
	}
	public function comercial_invoice_select_category_lcllocal_post(){
		
		$id = $this->input->post('id_commercial');
		$teknisi = $this->input->post('teknisi');
		
		$select_category = $this->input->post('selected_value');
		
		$comercialInvoice = PenjualanfromchinaModel::find($id);
		$comercialInvoice->status= 'process';
		$comercialInvoice ->category_comercial_invoice=$select_category;
		$comercialInvoice->save();
		$comercialInvoice_detail = PenjualanfromchinadetailModel::where('id_penjualanfromchina',$id)->get();
		$total_price_without_tax_sum = PenjualanfromchinadetailModel::where('id_penjualanfromchina', $id)
         ->sum('total_price_without_tax');
         

        $status_ppn_exists = PenjualanfromchinadetailModel::where('id_penjualanfromchina', $id)
	    ->where('status_ppn', 1)
	    ->exists();
	    $status_includeppn_exists = PenjualanfromchinaModel::where('id', $id)
	    ->where('status_includeppn', 1)
	    ->exists();

		$new_variable = $status_ppn_exists ? '1' : '0';
		$statusincludeppn_variable = $status_includeppn_exists ? '1' : '0';
		
		if($select_category=='lcl'){
			if($comercialInvoice->name_db == 'PT'){
						$lastpembelian 			= PembelianlclModel::where('name_db', 'PT')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
							}
						}
			}else{
						$lastpembelian 	= PembelianlclModel::where('name_db', 'UD')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LCLUD-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLUD-'.$year.''.$month.'-001';
							}
						}
			}	
		}
		else{
			if($comercialInvoice->name_db == 'PT'){
						$lastpembelian 			= PembelianlclModel::where('name_db', 'PT')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LOCALPT-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
							}
						}
			}else{
						$lastpembelian 	= PembelianlclModel::where('name_db', 'UD')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
							}
						}
			}		
		}

		$pembelian_details = PembelianlcldetailModel::where('id_penjualanfromchina', $comercialInvoice->id)->get();
		
		
		if (!$pembelian_details->isEmpty()) {
			foreach ($pembelian_details as $detail) {
 				$detail->delete();
			}
			
			$pembelianlcl_notdetail = PembelianlclModel::where('id',$pembelian_details[0]->id)->first();
			if (!$pembelianlcl_notdetail->isEmpty()) {
				foreach ($pembelianlcl_notdetail as $pembelian) {
					 $pembelian->delete();
				}
			
			}
		}
		
	
		
		
		$pembelianlcl 					= new PembelianlclModel;
		$pembelianlcl->name_db			= $comercialInvoice->name_db;
		$pembelianlcl->invoice 			=$newcodeorder;
		$pembelianlcl->tgl_transaksi	= $comercialInvoice->date;
		$pembelianlcl->noreferensi		= $comercialInvoice->noreferensi ?? 0;
		$pembelianlcl->id_teknisi		= $teknisi;
		$pembelianlcl->id_termin		= $comercialInvoice->id_termin ?? 1;
		$pembelianlcl->id_account		= $comercialInvoice->id_account ?? 2;
		$pembelianlcl->id_cabang = $comercialInvoice->cabang ?? 0;
		if ($pembelianlcl->id_cabang == 0) {
			$pembelianlcl->id_cabang = 1;
		}

		$pembelianlcl->id_matauang		= $comercialInvoice->id_matauang;
		$pembelianlcl->id_supplier 		= $comercialInvoice->id_supplier;
		$pembelianlcl->subtotal 		= $total_price_without_tax_sum ?? 0;
		$pembelianlcl->subtotal_idr		= 0;
		$pembelianlcl->discount         = $comercialInvoice->discount_percent ?? 0;
		$pembelianlcl->discount_nominal = $comercialInvoice->discount_nominal ?? 0;
		$pembelianlcl->ppn =  0;
		$pembelianlcl->draft =  'draft';
		$pembelianlcl->status = 1;
		$pembelianlcl->status_converttorupiah = 0;
		$pembelianlcl->nominal_convert = 0;
		$pembelianlcl->category_convert = 'default';
		$pembelianlcl->status_ppn = $new_variable;
		$pembelianlcl->status_includeppn =  $statusincludeppn_variable ?? 0;
		$pembelianlcl->status_bayar = 'belumlunas';
		$pembelianlcl->status_process = 'requested';
		$pembelianlcl->status_terima = 0;
		$pembelianlcl->status_deleted = 0;
		$pembelianlcl->keterangan = $comercialInvoice->keterangan ?? '';
		$pembelianlcl->category = $comercialInvoice->category;
		$pembelianlcl->category_transaksi 		=$select_category;
			
		if($pembelianlcl->save()){
			
				for ($index=0; $index < count($comercialInvoice_detail); $index++) { 
					$pembelianlcl_detail 					= new PembelianlcldetailModel;
					$pembelianlcl_detail->id_pembelianlcl   = $pembelianlcl->id;
					$pembelianlcl_detail->id_penjualanfromchina = $comercialInvoice_detail[$index]['id_penjualanfromchina'];
					$pembelianlcl_detail->id_restok 			= $comercialInvoice_detail[$index]['id_restok'];

					$restokobj 								=  RestokModel::where('id',$comercialInvoice_detail[$index]['id_restok'])->first();
					$pembelianlcl_detail->id_barang 			= $restokobj->id_barang;					
					$pembelianlcl_detail->id_gudang 		= $comercialInvoice_detail[$index]['gudang'] ?? 7;
					// dd(json_encode($pembelianlcl_detail));
					$pembelianlcl_detail->qty 		= $comercialInvoice_detail[$index]['qty'];
					$pembelianlcl_detail->qty_terima 		= $comercialInvoice_detail[$index]['qty_terima'];
					$pembelianlcl_detail->price 			=$comercialInvoice_detail[$index]['unit_price_without_tax'];
					$pembelianlcl_detail->price_idr 			=0;
					$pembelianlcl_detail->subtotal 			= $comercialInvoice_detail[$index]['total_price_without_tax'];
					$pembelianlcl_detail->subtotal_idr 			=0;
					$pembelianlcl_detail->disc 				=0;
					$pembelianlcl_detail->ppn 				=0;
					$pembelianlcl_detail->status_ppn 		= $comercialInvoice_detail[$index]['status_ppn'] ?? 0;
					$pembelianlcl_detail->status_terima 		= $comercialInvoice_detail[$index]['status_terima'];
					$pembelianlcl_detail->status_deleted 	=0;
					// dd(json_encode($pembelianlcl_detail));
					$pembelianlcl_detail->save();

				}
				$data['auth'] 								= true;
				$data['msg'] 								= $pembelianlcl->category_transaksi.' berhasil ditambahkan';

				echo toJson($data);
				return;
		}
		
	}
		

	public function comercial_invoice_tambah_post(){
			$modeadmin 						= $this->input->post('modeadmin') ??0;
			
			if($modeadmin == '1'){

				$rulesone = [
					'required' 	=> [
						['invoicenumber'],['contractnumber'],['packingnumber']
					]
				];

				$validateone 	= Validation::check($rulesone,'post');
				if(!$validateone->auth){
					echo goResult(false,$validateone->msg);
					return;
				}
			}

			$rules = [
				'required' 	=> [
					['database'],['tgl_transaksi'],['supplier'],['name_perusahaan']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			$chinese_name 					= $this->input->post('chinese_name'); //star
			$english_name 					= $this->input->post('english_name'); //star
			// dd($chinese_name,$english_name);
			$length_m 						= $this->input->post('length_m');
			$width_m 						= $this->input->post('width_m');
			$height_m 						= $this->input->post('height_m');
			$length_p 						= $this->input->post('length_p'); //star
			$width_p 						= $this->input->post('width_p'); //star
			$height_p 						= $this->input->post('height_p'); //star
			$model 							= $this->input->post('model');
			$brand 							= $this->input->post('brand');
			$qty 							= $this->input->post('qty'); //star
			$nett_weight 					= $this->input->post('nett_weight'); //star
			$gross_weight 					= $this->input->post('gross_weight'); //star
			$cbm_volume 					= $this->input->post('cbm_volume');
			$hs_code 						= $this->input->post('hs_code');
			$price 							= $this->input->post('price');
			$price_usd 						= $this->input->post('price_usd');
			$total 							= $this->input->post('total');
			$total_usd 						= $this->input->post('total_usd');
			$use 							= $this->input->post('use');
			$restok 						= $this->input->post('restok');
			$category_comercial_invoice 	= $this->input->post('category_comercial_invoice');
			$id_barang =$this->input->post('id_item');
	
			$countname =	0;
			for ($i=0; $i < count($chinese_name); $i++) { 
				if($chinese_name[$i] == ''){
					$countname = $countname + 1;
				}
			}

			$countenglish =	0;
			for ($i=0; $i < count($english_name); $i++) { 
				if($english_name[$i] == ''){
					$countenglish = $countenglish + 1;
				}
			}

			$countlength =	0;
			for ($i=0; $i < count($length_p); $i++) { 
				if($length_p[$i] == ''){
					$countlength = $countlength + 1;
				}
			}

			$countwidth =	0;
			for ($i=0; $i < count($width_p); $i++) { 
				if($width_p[$i] == ''){
					$countwidth = $countwidth + 1;
				}
			}

			$countheight =	0;
			for ($i=0; $i < count($height_p); $i++) { 
				if($height_p[$i] == ''){
					$countheight = $countheight + 1;
				}
			}

			$codeorder 	= PenjualanfromchinaModel::whereDate('created_at', '=', date('Y-m-d'))->desc()->get();
			$isToday 	= explode('-', date('Y-m-d'));
			$isYear 	= $isToday[0];
			$year 		= substr($isYear, -2);
			$month 		= $isToday[1];
			$day 		= $isToday[2];
			if(!$codeorder){
				$newcodeorder 		= $year.''.$month.''.$day.'01';
			}else{
				$newcode 			= count($codeorder) + 1;
				if($newcode > 0 && $newcode <= 9){
					$newcodeorder 	= $year.''.$month.''.$day.'0'.$newcode;
				}else{
					$newcodeorder 	= $year.''.$month.''.$day.$newcode;
				}
			}

			if($modeadmin == '1'){
				$invoiceno 						= $this->input->post('invoicenumber');
				$packingno 						= $this->input->post('contractnumber');
				$contractno 					= $this->input->post('packingnumber');
			}else{
				$invoiceno 						= $newcodeorder;
				$packingno 						= $newcodeorder;
				$contractno 					= $newcodeorder;
			}

			$commercialinvoice 					= new PenjualanfromchinaModel;
			$commercialinvoice->invoice_no 		= $invoiceno;
			$commercialinvoice->packing_no 		= $packingno;
			$commercialinvoice->contract_no 	= $contractno;
			$commercialinvoice->mode_admin 		= $modeadmin;

			$commercialinvoice->name_db 		= $this->input->post('database');
			$commercialinvoice->id_supplier 	= $this->input->post('supplier');
			$commercialinvoice->id_supplierbank = $this->input->post('supplierbank') ?? 0;

			$commercialinvoice->name 			= $this->input->post('name_perusahaan');
			$commercialinvoice->address 		= $this->input->post('address');
			$commercialinvoice->city 			= $this->input->post('city');
			$commercialinvoice->phone 			= $this->input->post('telephone');
			$commercialinvoice->date 			= $this->input->post('tgl_transaksi');
			$commercialinvoice->incoterms 		= $this->input->post('incoterms');
			$commercialinvoice->location 		= $this->input->post('location');
			$commercialinvoice->id_matauang 	= $this->input->post('currency');
			if($this->input->post('currency')==3){
				$commercialinvoice->currency 	= 'RMB';
			}elseif($this->input->post('currency')==3){
				$commercialinvoice->currency 	= 'USD';
			}
			$commercialinvoice->freight_cost 	= $this->input->post('freight_cost');
			$commercialinvoice->insurance 		= $this->input->post('insurance');
			$commercialinvoice->bank_name 			= $this->input->post('bank_name');
			$commercialinvoice->bank_address 		= $this->input->post('bank_address');
			$commercialinvoice->swift_code 			= $this->input->post('swift_code');
			$commercialinvoice->account_no 			= $this->input->post('account_no');
			$commercialinvoice->beneficiary_name 	= $this->input->post('beneficiary_name');
			$commercialinvoice->beneficiary_address = $this->input->post('beneficiary_address');
			if($category_comercial_invoice != null){
				$commercialinvoice->category_comercial_invoice = $category_comercial_invoice;
			}

			if($commercialinvoice->save()){
			
				for ($i=0; $i < count($chinese_name); $i++) { 
					$saledetail 							= new PenjualanfromchinadetailModel;
					
					$saledetail->id_penjualanfromchina 		= $commercialinvoice->id;
					$saledetail->id_restok 					= $restok[$i];
					$saledetail->name 						= $chinese_name[$i];
					$saledetail->name_english 				= $english_name[$i];
					$saledetail->model 						= $model[$i];
					$saledetail->brand 						= $brand[$i];
					$saledetail->length_m 					= $length_m[$i];
					$saledetail->width_m 					= $width_m[$i];
					$saledetail->height_m 					= $height_m[$i];
					$saledetail->length_p 					= $length_p[$i];
					$saledetail->width_p 					= $width_p[$i];
					$saledetail->height_p 					= $height_p[$i];
					$saledetail->qty 						= $qty[$i];
					$saledetail->nett_weight 				= $nett_weight[$i];
					$saledetail->gross_weight 				= $gross_weight[$i];
					$saledetail->cbm 						= $cbm_volume[$i];
					$saledetail->hs_code 					= $hs_code[$i];
					$saledetail->unit_price_without_tax		= $price[$i];
					$saledetail->unit_price_usd				= $price_usd[$i];
					$saledetail->total_price_without_tax 	= $total[$i];
					$saledetail->total_price_usd 			= $total_usd[$i];
					$saledetail->use_name 					= $use[$i];
					$saledetail->save();
					// dd('aaaa');
					$restokid 								= RestokModel::find($restok[$i]);

					if($restokid !=null){

						$restokid->status 						= 'process';

						$restokid->save();	
				
					

						$productid 								= BarangModel::find($restokid->id_barang);
						if($chinese_name[$i] != $productid->name_china){
							$productid->name_china 				= $chinese_name[$i];
						}

						if($english_name[$i] != $productid->name_english){
							$productid->name_english 			= $english_name[$i];
						}

						$productid->save();
					}
					// else{
					// 	$restok_tambah = new RestokModel;
					// 	$restok_tambah->id_barang = $id_barang[$i];
					// 	$restok_tambah->id_teknisi = $id_barang[$i];
					// 	$restok_tambah->id_supplier = $id_barang[$i];
					// 	$restok_tambah->jml_permintaan = $id_barang[$i];
					// 	$restok_tambah->jml_datang = $id_barang[$i];
					// 	$restok_tambah->tgl_request = $id_barang[$i];

					// }		
				}

				$data['idcommercial'] 						= $commercialinvoice->id;
				$data['idrestok'] = $saledetail->id_restok;
				$data['auth'] 								= true;
				$data['msg'] 								= 'Commercialinvoice berhasil ditambahkan';

				echo toJson($data);
				return;
			}else{
				echo goResult(false, "Commercialinvoice gagal dibuat");
				return;
			}
	}

	public function comercial_invoice_tambah_new_post(){
			$modeadmin 						= $this->input->post('modeadmin') ??0;
			
			if($modeadmin == '1'){

				$rulesone = [
					'required' 	=> [
						['invoicenumber'],['contractnumber'],['packingnumber']
					]
				];

				$validateone 	= Validation::check($rulesone,'post');
				if(!$validateone->auth){
					echo goResult(false,$validateone->msg);
					return;
				}
			}

			$rules = [
				'required' 	=> [
					['database'],['tgl_transaksi'],['supplier'],['name_perusahaan']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			$chinese_name 					= $this->input->post('chinese_name'); //star
			$english_name 					= $this->input->post('english_name'); //star
			// dd($chinese_name,$english_name);
			$length_m 						= $this->input->post('length_m');
			$width_m 						= $this->input->post('width_m');
			$height_m 						= $this->input->post('height_m');
			$length_p 						= $this->input->post('length_p'); //star
			$width_p 						= $this->input->post('width_p'); //star
			$height_p 						= $this->input->post('height_p'); //star
			$model 							= $this->input->post('model');
			$brand 							= $this->input->post('brand');
			$qty 							= $this->input->post('qty'); //star
			$nett_weight 					= $this->input->post('nett_weight'); //star
			$gross_weight 					= $this->input->post('gross_weight'); //star
			$cbm_volume 					= $this->input->post('cbm_volume');
			$hs_code 						= $this->input->post('hs_code');
			$price 							= $this->input->post('price');
			$price_usd 						= $this->input->post('price_usd');
			$total 							= $this->input->post('total');
			$total_usd 						= $this->input->post('total_usd');
			$use 							= $this->input->post('use');
			$status_includeppn 				= $this->input->post('status_includeppn');

			$status_ppn 				= $this->input->post('status_ppn');
			$gudang 						= $this->input->post('gudang');
			$restok 						= $this->input->post('restok');
			$discount_nominal 						= $this->input->post('discount_nominal');
			$discount_percent 						= $this->input->post('discount_percent');
			$noreferensi 						= $this->input->post('noreferensi');
			$category_comercial_invoice 	= $this->input->post('category_comercial_invoice');
			$id_barang =$this->input->post('id_item');
	
			$countname =	0;
			for ($i=0; $i < count($chinese_name); $i++) { 
				if($chinese_name[$i] == ''){
					$countname = $countname + 1;
				}
			}

			$countenglish =	0;
			for ($i=0; $i < count($english_name); $i++) { 
				if($english_name[$i] == ''){
					$countenglish = $countenglish + 1;
				}
			}

			$countlength =	0;
			for ($i=0; $i < count($length_p); $i++) { 
				if($length_p[$i] == ''){
					$countlength = $countlength + 1;
				}
			}

			$countwidth =	0;
			for ($i=0; $i < count($width_p); $i++) { 
				if($width_p[$i] == ''){
					$countwidth = $countwidth + 1;
				}
			}

			$countheight =	0;
			for ($i=0; $i < count($height_p); $i++) { 
				if($height_p[$i] == ''){
					$countheight = $countheight + 1;
				}
			}

			$codeorder 	= PenjualanfromchinaModel::whereDate('created_at', '=', date('Y-m-d'))->desc()->get();
			$isToday 	= explode('-', date('Y-m-d'));
			$isYear 	= $isToday[0];
			$year 		= substr($isYear, -2);
			$month 		= $isToday[1];
			$day 		= $isToday[2];
			if(!$codeorder){
				$newcodeorder 		= $year.''.$month.''.$day.'01';
			}else{
				$newcode 			= count($codeorder) + 1;
				if($newcode > 0 && $newcode <= 9){
					$newcodeorder 	= $year.''.$month.''.$day.'0'.$newcode;
				}else{
					$newcodeorder 	= $year.''.$month.''.$day.$newcode;
				}
			}

			if($modeadmin == '1'){
				$invoiceno 						= $this->input->post('invoicenumber');
				$packingno 						= $this->input->post('contractnumber');
				$contractno 					= $this->input->post('packingnumber');
			}else{
				$invoiceno 						= $newcodeorder;
				$packingno 						= $newcodeorder;
				$contractno 					= $newcodeorder;
			}

			$commercialinvoice 					= new PenjualanfromchinaModel;
			$commercialinvoice->invoice_no 		= $invoiceno;
			$commercialinvoice->packing_no 		= $packingno;
			$commercialinvoice->contract_no 	= $contractno;
			$commercialinvoice->mode_admin 		= $modeadmin;

			$commercialinvoice->name_db 		= $this->input->post('database');
			$commercialinvoice->id_supplier 	= $this->input->post('supplier');
			$commercialinvoice->id_supplierbank = $this->input->post('supplierbank') ?? 0;

			$commercialinvoice->name 			= $this->input->post('name_perusahaan');
			$commercialinvoice->cabang 			= $this->input->post('cabang');
			$commercialinvoice->category 		= $this->input->post('category');
			$commercialinvoice->id_account 		= $this->input->post('id_account');
			$commercialinvoice->id_termin 		= $this->input->post('termin');
			$commercialinvoice->address 		= $this->input->post('address');
			$commercialinvoice->city 			= $this->input->post('city');
			$commercialinvoice->phone 			= $this->input->post('telephone');
			$commercialinvoice->date 			= $this->input->post('tgl_transaksi');
			$commercialinvoice->incoterms 		= $this->input->post('incoterms');
			$commercialinvoice->location 		= $this->input->post('location');
			$commercialinvoice->id_matauang 	= $this->input->post('currency');
			$commercialinvoice->freight_cost 	= $this->input->post('freight_cost');
			$commercialinvoice->insurance 		= $this->input->post('insurance');
			$commercialinvoice->bank_name 			= $this->input->post('bank_name');
			$commercialinvoice->bank_address 		= $this->input->post('bank_address');
			$commercialinvoice->swift_code 			= $this->input->post('swift_code');
			$commercialinvoice->account_no 			= $this->input->post('account_no');
			$commercialinvoice->beneficiary_name 	= $this->input->post('beneficiary_name');
			$commercialinvoice->beneficiary_address = $this->input->post('beneficiary_address');
			$commercialinvoice->status_includeppn = $status_includeppn;

			$commercialinvoice->discount_nominal = $discount_nominal;
			$commercialinvoice->discount_percent = $discount_percent;
			$commercialinvoice->noreferensi = $noreferensi;
			$commercialinvoice->keterangan = $this->input->post('keterangan');
			if($category_comercial_invoice != null){
				$commercialinvoice->category_comercial_invoice = $category_comercial_invoice;
			}

			if($commercialinvoice->save()){
			
				for ($i=0; $i < count($chinese_name); $i++) { 
					$saledetail 							= new PenjualanfromchinadetailModel;
					
					$saledetail->id_penjualanfromchina 		= $commercialinvoice->id;
					$saledetail->id_restok 					= $restok[$i];
					$saledetail->name 						= $chinese_name[$i];
					$saledetail->name_english 				= $english_name[$i];
					$saledetail->model 						= $model[$i];
					$saledetail->brand 						= $brand[$i];
					$saledetail->length_m 					= $length_m[$i];
					$saledetail->width_m 					= $width_m[$i];
					$saledetail->height_m 					= $height_m[$i];
					$saledetail->length_p 					= $length_p[$i];
					$saledetail->width_p 					= $width_p[$i];
					$saledetail->height_p 					= $height_p[$i];
					$saledetail->qty 						= $qty[$i];
					$saledetail->nett_weight 				= $nett_weight[$i];
					$saledetail->gross_weight 				= $gross_weight[$i];
					$saledetail->cbm 						= $cbm_volume[$i];
					$saledetail->hs_code 					= $hs_code[$i];
					$saledetail->unit_price_without_tax		= $price[$i];
					$saledetail->unit_price_usd				= $price_usd[$i];
					$saledetail->total_price_without_tax 	= $total[$i];
					$saledetail->total_price_usd 			= $total_usd[$i];
					$saledetail->use_name 					= $use[$i];
					$saledetail->status_ppn 					= $status_ppn[$i];
					$saledetail->gudang 					= $gudang[$i];
					$saledetail->save();
					// dd('aaaa');
					$restokid 								= RestokModel::find($restok[$i]);

					if($restokid !=null){

						$restokid->status 						= 'process';

						$restokid->save();	
				
					

						$productid 								= BarangModel::find($restokid->id_barang);
						if($chinese_name[$i] != $productid->name_china){
							$productid->name_china 				= $chinese_name[$i];
						}

						if($english_name[$i] != $productid->name_english){
							$productid->name_english 			= $english_name[$i];
						}

						$productid->save();
					}
					// else{
					// 	$restok_tambah = new RestokModel;
					// 	$restok_tambah->id_barang = $id_barang[$i];
					// 	$restok_tambah->id_teknisi = $id_barang[$i];
					// 	$restok_tambah->id_supplier = $id_barang[$i];
					// 	$restok_tambah->jml_permintaan = $id_barang[$i];
					// 	$restok_tambah->jml_datang = $id_barang[$i];
					// 	$restok_tambah->tgl_request = $id_barang[$i];

					// }		
				}

				$data['idcommercial'] 						= $commercialinvoice->id;
				$data['idrestok'] = $saledetail->id_restok;
				$data['auth'] 								= true;
				$data['msg'] 								= 'Commercialinvoice berhasil ditambahkan';

				echo toJson($data);
				return;
			}else{
				echo goResult(false, "Commercialinvoice gagal dibuat");
				return;
			}
	}
	public function hapus_comercial_invoice_delete($id){
		  $penjualan              = PenjualanfromchinaModel::find($id);

            if(!$penjualan){
                echo goResult(false, 'Maaf, commercialinvoice tidak ada');
                return;
            }

            if($penjualan->status != 'requested'){
                echo goResult(false, 'Maaf, item sudah di proses di FCL / LCL');
                return;
            }

            foreach ($penjualan->detail as $key => $value) {

                if($value->id_restok != null || $value->id_restok != ''){
                	$penjualan->status_deleted  = '1';
            		$penjualan->save();

                    $restokId           = RestokModel::find($value->id_restok);

                    $restokId->status   = 'requested';
                    $restokId->save();
                }
            }

            
            echo goResult(true, 'Data anda berhasil dihapus');
            return;
	}

	public function comercial_invoice_edit1_post(){
		
			$id = $this->input->post('id');
			$commercialinvoice 				= PenjualanfromchinaModel::find($id);
			if(!$commercialinvoice){
				echo goResult(false, 'Maaf, Commercialinvoice tidak ada');
				return;
			}
			$id_detail 	 	= $this->input->post('id_detail');
			$id_barang 	 	= $this->input->post('id_barang');
			$name 	 		= $this->input->post('name');
			$name_chinese 	= $this->input->post('name_chinese');
			$name_english 	= $this->input->post('name_english');
			$model 	 		= $this->input->post('model');
			$brand 	 		= $this->input->post('brand');
			$length 	 	= $this->input->post('length') / 100;
			$width 	 		= $this->input->post('width') / 100;
			$height 	 	= $this->input->post('height') / 100;
			$length_p 	 	= $this->input->post('length_p') / 100;
			$width_p 	 	= $this->input->post('width_p') / 100;
			$height_p 	 	= $this->input->post('height_p') / 100;
			$nett_weight 	= $this->input->post('nett_weight');
			$gross_weight 	= $this->input->post('gross_weight');
			$hscode 		= $this->input->post('hscode');
			$product 		= BarangModel::find($id_barang);
			if(!$product){
				echo goResult(false, 'Product not found');
				return;
			}

			if($id_detail != 0){
				$coomercialdetail 	= PenjualanfromchinadetailModel::find($id_detail);
				if(!$coomercialdetail){
					echo goResult(false, 'Commercial not found');
					return;
				}
			}

			// $cbm 						= $length * $width * $height;
			$product->name 				= $name;
			$product->name_china 		= $name_chinese;
			$product->name_english 		= $name_english;
			$product->model 			= $model;
			$product->merk 				= $brand;
			$product->long 				= round($length, 2);
			$product->width 			= round($width, 2);
			$product->height 			= round($height, 2);
			$product->cbm 				= round($cbm, 2);
			$product->long_p 			= round($length_p, 2);
			$product->width_p 			= round($width_p, 2);
			$product->height_p 			= round($height_p, 2);
			$product->weight 			= $gross_weight;
			$product->nett_weight 		= $nett_weight;
			$product->save();

			if($hscode != ''){
				$producthscode 				= new BarangHscodeModel;
				$producthscode->id_barang 	= $id_barang;
				$producthscode->code 		= $hscode;
				$producthscode->save();
			}

			if($id_detail != 0){
				$coomercialdetail->name 			= $name_chinese;
				$coomercialdetail->name_english 	= $name_english;
				$coomercialdetail->model 			= $model;
				$coomercialdetail->brand 			= $brand;
				$coomercialdetail->length_m 		= round($this->input->post('length'), 2);
				$coomercialdetail->width_m 			= round($this->input->post('width'), 2);
				$coomercialdetail->height_m 		= round($this->input->post('height'), 2);
				$coomercialdetail->cbm 				= round($this->input->post('cbm'), 2);
				$coomercialdetail->length_p 		= round($this->input->post('length_p'), 2);
				$coomercialdetail->width_p 			= round($this->input->post('width_p'), 2);
				$coomercialdetail->height_p 		= round($this->input->post('height_p'), 2);
				$coomercialdetail->gross_weight 	= $gross_weight;
				$coomercialdetail->nett_weight 		= $nett_weight;
				$coomercialdetail->unit_price_without_tax 		= $this->input->post('unit_price_without_tax');
				$coomercialdetail->total_price_without_tax = $this->input->post('total_price_without_tax');
				$coomercialdetail->unit_price_usd =  $this->input->post('unit_price_usd');
				$coomercialdetail->total_price_usd =  $this->input->post('total_price_usd');
				$coomercialdetail->use_name =  $this->input->post('use_name');
				$coomercialdetail->hs_code 			= $hscode;
				$coomercialdetail->save();
			}

			echo goResult(true, 'Item success for update');
			return;
			
	}

	public function comercial_invoice_edit2_post(){
			
			$id = $this->input->post('id');
			$commercialinvoice 				= PenjualanfromchinaModel::find($id);
			if(!$commercialinvoice){
				echo goResult(false, 'Maaf, Commercialinvoice tidak ada');
				return;
			}

			$modeadmin 						= $this->input->post('modeadmin');
			
			if($modeadmin == '1'){
				$rulesone = [
					'required' 	=> [
						['invoicenumber'],['contractnumber'],['packingnumber']
					]
				];

				$validateone 	= Validation::check($rulesone,'post');
				if(!$validateone->auth){
					echo goResult(false,$validateone->msg);
					return;
				}
			}

			$rules = [
				'required' 	=> [
					['database'],['tgl_transaksi'],['supplier'],['name_perusahaan']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			$chinese_name = $this->input->post('chinese_name') ?? '';
			$english_name = $this->input->post('english_name') ?? '';
			$length_m = $this->input->post('length_m') ?? '';
			$width_m = $this->input->post('width_m') ?? '';
			$height_m = $this->input->post('height_m') ?? '';
			$length_p = $this->input->post('length_p') ?? '';
			$width_p = $this->input->post('width_p') ?? '';
			$height_p = $this->input->post('height_p') ?? '';
			$model = $this->input->post('model') ?? '';
			$brand = $this->input->post('brand') ?? '';
			$qty = $this->input->post('qty') ?? '';
			$nett_weight = $this->input->post('nett_weight') ?? '';
			$gross_weight = $this->input->post('gross_weight') ?? '';
			$cbm_volume = $this->input->post('cbm_volume') ?? '';
			$hs_code = $this->input->post('hs_code') ?? '';
			$price = $this->input->post('price') ?? '';
			$price_usd = $this->input->post('price_usd') ?? '';
			$total = $this->input->post('total') ?? '';
			$total_usd = $this->input->post('total_usd') ?? '';
			$use = $this->input->post('use') ?? '';
			$restok = $this->input->post('restok') ?? '';

			$countname =	0;
			for ($i=0; $i < count($chinese_name); $i++) { 
				if($chinese_name[$i] == ''){
					$countname = $countname + 1;
				}
			}

			$countenglish =	0;
			for ($i=0; $i < count($english_name); $i++) { 
				if($english_name[$i] == ''){
					$countenglish = $countenglish + 1;
				}
			}

			$countlength =	0;
			for ($i=0; $i < count($length_p); $i++) { 
				if($length_p[$i] == ''){
					$countlength = $countlength + 1;
				}
			}

			$countwidth =	0;
			for ($i=0; $i < count($width_p); $i++) { 
				if($width_p[$i] == ''){
					$countwidth = $countwidth + 1;
				}
			}

			$countheight =	0;
			for ($i=0; $i < count($height_p); $i++) { 
				if($height_p[$i] == ''){
					$countheight = $countheight + 1;
				}
			}

		
			//backoldrequested
			$backrequested 						= PenjualanfromchinadetailModel::where('id_penjualanfromchina', $commercialinvoice->id)->get();
			foreach ($backrequested as $key => $value) {
				$restokid 						= RestokModel::find($value->id_restok);
				$restokid->status 				= 'requested';
				$restokid->save();
			}

			if($modeadmin == '1'){
				$invoiceno 						= $this->input->post('invoicenumber');
				$packingno 						= $this->input->post('contractnumber');
				$contractno 					= $this->input->post('packingnumber');
			}else{
				$invoiceno 						= $commercialinvoice->invoice_no;
				$packingno 						= $commercialinvoice->packing_no;
				$contractno 					= $commercialinvoice->contract_no;
			}

			$commercialinvoice->invoice_no 		= $invoiceno;
			$commercialinvoice->packing_no 		= $packingno;
			$commercialinvoice->contract_no 	= $contractno;
			$commercialinvoice->mode_admin 		= $modeadmin;
			$commercialinvoice->name_db 		= $this->input->post('database');
			$commercialinvoice->id_supplier 	= $this->input->post('supplier');
			$commercialinvoice->id_supplierbank = $this->input->post('supplierbank');
			$commercialinvoice->name 			= $this->input->post('name_perusahaan');
			$commercialinvoice->address 		= $this->input->post('address');
			$commercialinvoice->city 			= $this->input->post('city');
			$commercialinvoice->phone 			= $this->input->post('telephone');
			$commercialinvoice->date 			= $this->input->post('tgl_transaksi');
			$commercialinvoice->incoterms 		= $this->input->post('incoterms');
			$commercialinvoice->location 		= $this->input->post('location');
			$commercialinvoice->id_matauang 	= $this->input->post('currency');
			$commercialinvoice->freight_cost 	= $this->input->post('freight_cost');
			$commercialinvoice->insurance 		= $this->input->post('insurance');
			$commercialinvoice->bank_name 			= $this->input->post('bank_name');
			$commercialinvoice->bank_address 		= $this->input->post('bank_address');
			$commercialinvoice->swift_code 			= $this->input->post('swift_code');
			$commercialinvoice->account_no 			= $this->input->post('account_no');
			$commercialinvoice->beneficiary_name 	= $this->input->post('beneficiary_name');
			$commercialinvoice->beneficiary_address = $this->input->post('beneficiary_address');
			if($commercialinvoice->save()){
				PenjualanfromchinadetailModel::where('id_penjualanfromchina', $commercialinvoice->id)->delete();

				for ($i=0; $i < count($chinese_name); $i++) { 
					$saledetail 							= new PenjualanfromchinadetailModel;
					$saledetail->id_penjualanfromchina 		= $commercialinvoice->id;
					$saledetail->id_restok 					= $restok[$i];
					$saledetail->name 						= $chinese_name[$i];
					$saledetail->name_english 				= $english_name[$i];
					$saledetail->model 						= $model[$i];
					$saledetail->brand 						= $brand[$i];
					$saledetail->length_m 					= $length_m[$i];
					$saledetail->width_m 					= $width_m[$i];
					$saledetail->height_m 					= $height_m[$i];
					$saledetail->length_p 					= $length_p[$i];
					$saledetail->width_p 					= $width_p[$i];
					$saledetail->height_p 					= $height_p[$i];
					$saledetail->qty 						= $qty[$i];
					$saledetail->nett_weight 				= $nett_weight[$i];
					$saledetail->gross_weight 				= $gross_weight[$i];
					$saledetail->cbm 						= $cbm_volume[$i];
					$saledetail->hs_code 					= $hs_code[$i];
					$saledetail->unit_price_without_tax		= $price[$i];
					$saledetail->unit_price_usd				= $price_usd[$i];
					$saledetail->total_price_without_tax 	= $total[$i];
					$saledetail->total_price_usd 			= $total_usd[$i];
					$saledetail->use_name 					= $use[$i];
					$saledetail->save();
					
					$restokid 								= RestokModel::find($restok[$i]);
					if($restokid->status != 'complete'){
						$restokid->status 					= 'process';
					}
					$restokid->save();

					$productid 								= BarangModel::find($restokid->id_barang);
					if($chinese_name[$i] != $productid->name_china){
						$productid->name_china 				= $chinese_name[$i];
					}

					if($english_name[$i] != $productid->name_english){
						$productid->name_english 			= $english_name[$i];
					}
					$productid->save();
				}

				echo goResult(true, "Commercialinvoice berhasil di edit");
				return;
			}else{
				echo goResult(false, "Commercialinvoice gagal di edit");
				return;
			}
	}


	public function comercial_invoice_add_proses_importdata_post(){
		$id_penjualanfromchina = $this->input->post('id');
		$commercialinvoice 				= PenjualanfromchinaModel::find($id_penjualanfromchina);
		$id_barang 	 	= $this->input->post('id_barang');
		$name 	 		= $this->input->post('name');
		$name_chinese 	= $this->input->post('name_chinese');
		$name_english 	= $this->input->post('name_english');
		$model 	 		= $this->input->post('model');
		$brand 	 		= $this->input->post('brand');
		$length 	 	= $this->input->post('length') / 100;
		$width 	 		= $this->input->post('width') / 100;
		$height 	 	= $this->input->post('height') / 100;
		$length_p 	 	= $this->input->post('length_p') / 100;
		$width_p 	 	= $this->input->post('width_p') / 100;
		$height_p 	 	= $this->input->post('height_p') / 100;
		$qty 		= $this->input->post('qty');
		$nett_weight 	= $this->input->post('nett_weight');
		$gross_weight 	= $this->input->post('gross_weight');
		$hscode 		= $this->input->post('hscode');
		$cbm 		= $this->input->post('cbm');

		$unit_price_without_tax 		= $this->input->post('unit_price_without_tax');
		$total_price_without_tax 		= $this->input->post('total_price_without_tax');
		$unit_price_usd 		= $this->input->post('unit_price_usd');
		$total_price_usd 		= $this->input->post('total_price_usd');
		$use 		= $this->input->post('use');

		$backrequested 						= PenjualanfromchinadetailModel::where('id_penjualanfromchina', $commercialinvoice->id)->get();
			foreach ($backrequested as $key => $value) {
				$restokid 						= RestokModel::find($value->id_restok);
				$restokid->status 				= 'requested';
				$restokid->save();
			}
		echo goResult(true, 'Import barang success');
		return;
	}
	

	public function commercial_invoice_edit_addpacking_post(){
		$id = $this->input->post('id');
		$commercialinvoice 				= PenjualanfromchinaModel::find($id);
			if(!$commercialinvoice){
				echo goResult(false, 'Commercialinvoice not found');
				return;
			}

			$numberpacking 					= $this->input->post('number_packing');
            
			$namepacking 					= $this->input->post('name_packing');
			if(!$namepacking){
				echo goResult(false, 'Packing not found');
				return;
			}

			$namepackingerror 				= 0;
			for ($i=0; $i < count($namepacking); $i++) { 
				if($namepacking[$i] == ''){
					$namepackingerror 		= $namepackingerror + 1;
				}
			}

			if($namepackingerror > 0){
				echo goResult(false, 'Packing name is required');
				return;
			}

			$itemerror 						= 0;
			$qtyzero 						= 0;
			$qtyerror 						= 0;

			for ($i=0; $i < count($namepacking); $i++) { 

				$itempacking 				= $this->input->post('itempacking_'.$numberpacking[$i]);
				$qtyitempacking 			= $this->input->post('qtyitempacking_'.$numberpacking[$i]);
				$itemiserror 				= 0;
				$qtyitemzero 				= 0;
				$qtyitemerror 				= 0;
				for ($j=0; $j < count($itempacking); $j++) { 
					if($itempacking == ''){
						$itemiserror 		= $itemiserror + 1;;
					}
					if($qtyitempacking[$j] == 0){
						$qtyitemzero 		= $qtyitemzero + 1;
					}
					if($qtyitempacking == ''){
						$qtyitemerror 		= $qtyitemerror + 1;;
					}
				}

				$itemerror 					= $itemerror + $itemiserror;
				$qtyzero 					= $qtyzero + $qtyitemzero;
				$qtyerror 					= $qtyerror + $qtyitemerror;
			}
							// dd($itempacking);
			if($itemerror > 0){
				echo goResult(false, 'Item is required');
				return;
			}
			
			if($qtyzero > 0){
				echo goResult(false, 'Qty of item is not zero');
				return;
			}

			if($qtyerror > 0){
				echo goResult(false, 'Qty of item is required');
				return;
			}

			$idpacking 								= array();
			$getoldpacking 							= PenjualanfromchinapackingModel::where('id_penjualanfromchina', $commercialinvoice->id)->get();

			foreach ($getoldpacking as $key => $value) {
				$idpacking[] 						= $value->id;
			}

			PenjualanfromchinapackingdetailModel::whereIn('id_penjualanfromchinapacking', $idpacking)->delete();
			PenjualanfromchinapackingModel::where('id_penjualanfromchina', $commercialinvoice->id)->delete();
			for ($i=0; $i < count($namepacking); $i++) { 
				$newpacking 						= new PenjualanfromchinapackingModel;
				$newpacking->id_penjualanfromchina 	= $commercialinvoice->id;
				$newpacking->name 					= $namepacking[$i];
				$newpacking->status 				= '1';
				if($newpacking->save()){
					$itempacking 					= $this->input->post('itempacking_'.$numberpacking[$i]);
					$qtyitempacking 				= $this->input->post('qtyitempacking_'.$numberpacking[$i]);

					for ($j=0; $j < count($itempacking); $j++) { 
						$newitemlist 								= new PenjualanfromchinapackingdetailModel;
						$newitemlist->id_penjualanfromchinapacking 	= $newpacking->id;
						$newitemlist->id_penjualanfromchinadetail 	= $itempacking[$j];
						$newitemlist->qty 							= $qtyitempacking[$j];
						$newitemlist->save();
					}
				}
			}

			echo goResult(true, 'Packing list success');
			return;
	}

	public function comercial_invoice_editview_get($id){

			$teknisi_id = $this->session->userdata('teknisi_id');

			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST

			if (!$teknisi_id) {



				$teknisi_id = $this->input->post('teknisi_id');

			}


			// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error

			if (!$teknisi_id) {



				$this->response([



					'success' => false,



					'message' => 'Teknisi tidak ada'



				], 401);



				return;

			}



			$teknisi = TeknisiModel::select('teknisi.*', 'position_karyawan.name as name_position')

			->join('position_karyawan', 'teknisi.id_position', '=', 'position_karyawan.id')

			->where('teknisi.id', $teknisi_id)

			->first();


			// Periksa apakah teknisi ditemukan

			if (!$teknisi) {



				// Teknisi tidak ditemukan, kirim respons error



				$this->response([



					'success' => false,



					'message' => 'Anda Belum Login'



				], 401);



				return;

			}

			if ($teknisi->name_position !== 'Manager' && $teknisi->name_position !== 'Web Developer') {



				// kondisi diatas akan dijalankan apabaila posisi selain web developer dan manager



				$this->response([



					'success' => false,



					'message' => 'Position tidak sesuai'



				], 401);



				return;

			}

				$penjualan 	= PenjualanfromchinaModel::with(['supplier_bank','detail','barang'])->find($id);



			if(!$penjualan){

				http_response_code(404);

				echo goResult(false,'penjualan not found');

				return;

			}



			$detail 	= PenjualanfromchinadetailModel::where('id_penjualanfromchina', $id)->get();
	
			$idproduct 	= array();

			$idorder 	= array();

			foreach ($detail as $key => $value) {

				if($value->restok){

					$idproduct[] 	= $value->restok->id_barang;

					$idorder[] 		= $value->restok->id;

				}
				else{
					$idproduct[]  = $value->id_restok;
					$idorder[]  = $value->id_restok;
				}

			}
			


			$restok 	= RestokModel::whereIn('id_barang', $idproduct)->where('status_deleted', '0')->desc()->get();



			$idrestok 	= array();

			foreach ($restok as $key => $value) {

				$idrestok[] = $value['id'];
			
			}

			$data['commercialinvoice'] 		= $penjualan;

			$data['hscodehistory']			= PenjualanfromchinadetailModel::with(['restok','commercialinvoice'])->whereIn('id_restok', $idrestok)->desc()->get();
			
			$idBarang = array();
			$idPenjualanFromChina = array();
				foreach ($data['hscodehistory']  as $key => $value) {


					$idBarang[] 	= $value->restok->id_barang;
					$idPenjualanFromChina[] = $value->id_penjualanfromchina;

			}
			
			
			$url = 'https://maxipro.id/images/barang/';


			$this->db->select('*');
			$this->db->from('barang'); // Sesuaikan dengan nama tabel BarangModel

			// Buat kondisi WHERE untuk setiap nilai dalam array $idBarang
			foreach ($idBarang as $id_com) {
			    $this->db->or_where('id', $id_com);
			}

			$query = $this->db->get();
			$data['barang'] = $query->result(); // Mengambil hasil query sebagai array dari objek BarangModel

			// Ubah properti image dengan menambahkan $url di depannya
			foreach ($data['barang'] as $barang) {
			    $barang->image = $url . $barang->image;
			}

			
			$data['penjualanfromchina'] = PenjualanfromchinaModel::with('matauang')->whereIn('id',$idPenjualanFromChina)->get();
			

			$data['idcommercial'] 			= $id;		
		
			
			$data['packinglist'] 			= PenjualanfromchinapackingModel::with('detail')->where('id_penjualanfromchina', $id)->where('status', '1')->asc()->get();
			
			$data['supplierbank'] 			= SupplierbankModel::with('matauang')->where('id_supplier', $penjualan->id_supplier)->where('status', '1')->asc()->get();
			

			$data['matauang'] = MatauangModel::where('status','1')->orderBy('name','ASC')->get();
			
			$this->db->select('*');
			$this->db->from('penjualanfromchina_detail');
			$this->db->join('restok','penjualanfromchina_detail.id_restok = restok.id');
			$this->db->where_in('penjualanfromchina_detail.id_restok',$idPenjualanFromChina);
			$query_restok = $this->db->get();
			$data['restok'] = $query_restok->result();

			


			$this->db->select('restok.*,barang.*, supplier.name as supplier_name'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			$this->db->where_in('restok.id', $idorder); 
			$this->db->order_by('restok.id', 'DESC'); 

			$query = $this->db->get();
			$data['listordercheck'] = $query->result(); 
			$id_baranghscodehistory =[];
			foreach ($data['listordercheck'] as $key => $value) {
				$id_baranghscodehistory[] = $value->id_barang;
			}
			$data['barang_hscodehistory'] = BarangHscodeModel::whereIn('id_barang',$id_baranghscodehistory)->get();
			// dd($id_baranghscodehistory);

			$this->db->select('restok.*, barang.*, supplier.name as supplier_name,restok.id as restok_id'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			$this->db->where_not_in('restok.id', $idorder); 
			$this->db->where('restok.id_supplier', $penjualan->id_supplier); 
			$this->db->where('restok.status', 'requested');
			$this->db->order_by('restok.id', 'DESC'); 

			$query = $this->db->get();
			$data['listorder'] = $query->result(); 
			$data['directory_gambar'] = 'https://maxipro.id/images/barang/';


			$idsupplier 		= array();

			$listordercheck 	= RestokModel::where('status_deleted', '0')->whereIn('id', $idorder)->groupBy('id_supplier')->get();

			foreach ($listordercheck as $key => $value) {

				$idsupplier[] 	= $value->id_supplier;

			}



			$listorder 			= RestokModel::where('status_deleted', '0')->whereNotIn('id', $idorder)->where('id_supplier', $penjualan->id_supplier)->where('status', 'requested')->groupBy('id_supplier')->get();

			foreach ($listorder as $key => $value) {

				$idsupplier[] 	= $value->id_supplier;

			}



			$data['supplier'] 	= SupplierModel::whereIn('id', $idsupplier)->where('status', '1')->orderBy('name', 'asc')->get();



			$data['supplier_all'] 			= SupplierModel::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
			
			$this->db->select('master_rmbtousd.*'); 
			$this->db->from('master_rmbtousd'); 
			
			

			$master_rmbtousd= $this->db->get();

			$data['masterrmbtousd'] = $master_rmbtousd->result();
			
			echo goResult(true,$data);
			return;

		

	}

		public function comercial_invoice_detail_get($id){

			$teknisi_id = $this->session->userdata('teknisi_id');

			// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST

			if (!$teknisi_id) {



				$teknisi_id = $this->input->post('teknisi_id');

			}


			// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error

			if (!$teknisi_id) {



				$this->response([



					'success' => false,



					'message' => 'Teknisi tidak ada'



				], 401);



				return;

			}



			$teknisi = TeknisiModel::select('teknisi.*', 'position_karyawan.name as name_position')

			->join('position_karyawan', 'teknisi.id_position', '=', 'position_karyawan.id')

			->where('teknisi.id', $teknisi_id)

			->first();


			// Periksa apakah teknisi ditemukan

			if (!$teknisi) {



				// Teknisi tidak ditemukan, kirim respons error



				$this->response([



					'success' => false,



					'message' => 'Anda Belum Login'



				], 401);



				return;

			}

			if ($teknisi->name_position !== 'Manager' && $teknisi->name_position !== 'Web Developer') {



				// kondisi diatas akan dijalankan apabaila posisi selain web developer dan manager



				$this->response([



					'success' => false,



					'message' => 'Position tidak sesuai'



				], 401);



				return;

			}

				$penjualan 	= PenjualanfromchinaModel::with(['supplier_bank','detail','barang'])->find($id);



			if(!$penjualan){

				http_response_code(404);

				echo goResult(false,'penjualan not found');

				return;

			}



			$detail 	= PenjualanfromchinadetailModel::where('id_penjualanfromchina', $id)->get();
	
			$idproduct 	= array();

			$idorder 	= array();

			foreach ($detail as $key => $value) {

				if($value->restok){

					$idproduct[] 	= $value->restok->id_barang;

					$idorder[] 		= $value->restok->id;

				}

			}



			$restok 	= RestokModel::whereIn('id_barang', $idproduct)->where('status_deleted', '0')->desc()->get();



			$idrestok 	= array();

			foreach ($restok as $key => $value) {

				$idrestok[] = $value['id'];
			
			}

			$data['commercialinvoice'] 		= $penjualan;

			$data['hscodehistory'] 			= PenjualanfromchinadetailModel::with(['restok','commercialinvoice'])->whereIn('id_restok', $idrestok)->desc()->get();
			
			$idBarang = array();
			$idPenjualanFromChina = array();
				foreach ($data['hscodehistory']  as $key => $value) {


					$idBarang[] 	= $value->restok->id_barang;
					$idPenjualanFromChina[] = $value->id_penjualanfromchina;

			}
			
			
			$url = 'https://maxipro.id/images/barang/';


			$this->db->select('*');
			$this->db->from('barang'); // Sesuaikan dengan nama tabel BarangModel

			// Buat kondisi WHERE untuk setiap nilai dalam array $idBarang
			foreach ($idBarang as $id_com) {
			    $this->db->or_where('id', $id_com);
			}

			$query = $this->db->get();
			$data['barang'] = $query->result(); // Mengambil hasil query sebagai array dari objek BarangModel

			// Ubah properti image dengan menambahkan $url di depannya
			foreach ($data['barang'] as $barang) {
			    $barang->image = $url . $barang->image;
			}

			
			$data['penjualanfromchina'] = PenjualanfromchinaModel::with('matauang')->whereIn('id',$idPenjualanFromChina)->get();
			

			$data['idcommercial'] 			= $id;		
		
			// dd($id);
			$data['packinglist'] 			= PenjualanfromchinapackingModel::with('detail')->where('id_penjualanfromchina', $id)->where('status', '1')->asc()->get();
			
			$data['supplierbank'] 			= SupplierbankModel::with('matauang')->where('id_supplier', $penjualan->id_supplier)->where('status', '1')->asc()->get();
			

			$data['matauang'] = MatauangModel::where('status','1')->orderBy('name','ASC')->get();
			
			$this->db->select('*');
			$this->db->from('penjualanfromchina_detail');
			$this->db->join('restok','penjualanfromchina_detail.id_restok = restok.id');
			$this->db->where_in('penjualanfromchina_detail.id_restok',$idPenjualanFromChina);
			$query_restok = $this->db->get();
			$data['restok'] = $query_restok->result();

			


			$this->db->select('restok.*, barang.*, supplier.name as supplier_name'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			$this->db->where_in('restok.id', $idorder); 
			$this->db->order_by('restok.id', 'DESC'); 

			$query = $this->db->get();
			$data['listordercheck'] = $query->result(); 

			$this->db->select('restok.*, barang.*, supplier.name as supplier_name,restok.id as restok_id'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			$this->db->where_not_in('restok.id', $idorder); 
			$this->db->where('restok.id_supplier', $penjualan->id_supplier); 
			$this->db->where('restok.status', 'requested');
			$this->db->order_by('restok.id', 'DESC'); 

			$query = $this->db->get();
			$data['listorder'] = $query->result(); 
			$data['directory_gambar'] = 'https://maxipro.id/images/barang/';


			$idsupplier 		= array();

			$listordercheck 	= RestokModel::where('status_deleted', '0')->whereIn('id', $idorder)->groupBy('id_supplier')->get();

			foreach ($listordercheck as $key => $value) {

				$idsupplier[] 	= $value->id_supplier;

			}



			$listorder 			= RestokModel::where('status_deleted', '0')->whereNotIn('id', $idorder)->where('id_supplier', $penjualan->id_supplier)->where('status', 'requested')->groupBy('id_supplier')->get();

			foreach ($listorder as $key => $value) {

				$idsupplier[] 	= $value->id_supplier;

			}



			$data['supplier'] 	= SupplierModel::whereIn('id', $idsupplier)->where('status', '1')->orderBy('name', 'asc')->get();



			$data['supplier_all'] 			= SupplierModel::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
			
			$this->db->select('master_rmbtousd.*'); 
			$this->db->from('master_rmbtousd'); 
			
			

			$master_rmbtousd= $this->db->get();

			$data['masterrmbtousd'] = $master_rmbtousd->result();
			
			echo goResult(true,$data);
			return;

		

		}
	public function commercial_invoice_importdata_post(){
		 

			$restok 	 	= $this->input->post('idrestok');
			$valuerestok 	= $this->input->post('valuerestok');
            
			$restokfix 		= array();
			for ($i=0; $i < count($valuerestok); $i++) { 
				if($valuerestok[$i] == '1'){
					$restokfix[] = $restok[$i];
				}
			}

			if(count($restokfix) <= 0){
				echo goResult(false, 'Order pembelian required');
				return;
			}
			// dd($restokfix);
			$orderpembelian 	= RestokModel::with('product')->whereIn('id', $restokfix)->where('status','requested')->desc()->get();
			// $this->db->select('restok.*, barang.*'); // Select all columns from both tables
			// $this->db->from('restok'); // Specify the main table
			// $this->db->join('barang', 'barang.id = restok.id_barang', 'left'); // Join with the product table
			// $this->db->where_in('restok.id', $restokfix); // Filter by IDs in $restokfix
			// $this->db->order_by('restok.id', 'DESC'); // Order by ID in descending order
			// $orderpembelian = $this->db->get()->result(); // Execute the query and get the result

			// dd('orderpembelian',json_encode($orderpembelian));
			if(!$orderpembelian){
				echo goResult(false, 'Order pembelian not found');
				return;
			}

			$idproduct 	= array();
			foreach ($orderpembelian as $key => $value) {
				$idproduct[] = $value->id_barang;
			}

			$restok 	= RestokModel::whereIn('id_barang', $idproduct)->where('status_deleted', '0')->desc()->get();
			$idrestok 	= array();
			foreach ($restok as $key => $value) {
				$idrestok[] = $value->id;
			}

			$data['hscodehistory'] 		= PenjualanfromchinadetailModel::with('restok', 'commercialinvoice')->whereIn('id_restok', $idrestok)->desc()->get();
			
			$data['matauang'] = MatauangModel::where('status',1)->get();
				$data['auth'] 				= true;
				$data['msg'] 				= 'success';
				$data['orderpembelian'] 	= $orderpembelian;
				$id_supplier_bank;
				foreach($data['orderpembelian'] as $key => $value){
					$id_supplier_bank = $value->id_supplier;
				}
			$data['gudang'] = GudangModel::where('status',1)->orderBy('name','asc')->get();
			
			$data['supplier'] = SupplierModel::where('id',$id_supplier_bank)->get();
			$data['supplierbank'] = SupplierbankModel::where('id_supplier',$id_supplier_bank)->get();
			// dd($id_supplier_bank);
			echo toJson($data);
			return;
	}

	public function lcl_importdata_post(){
		 

			$idcommercial 		= $this->input->post('idcommercial');
			
            if(!$idcommercial){
                
				echo goResult(false, 'Commercialinvoice is required');
				return;
			}
                
			$commercial 			= PenjualanfromchinaModel::find($idcommercial);
     
			$arrcommercialdetail 	= array();
			$valueerror 			= 0;
			foreach ($commercial->detail as $key => $result) {
				if($result->id_restok == NULL || $result->id_restok == ''){
					$valueerror = $valueerror + 1;
				}

				$arrcommercialdetail[] 		= array(
					'id' 			=> $result->id_penjualanfromchina,
					'idrestok' 		=> $result->id_restok,
					'iditem' 		=> $result->restok->id_barang,
					'nameitem' 		=> $result->restok->product->name,
					'imageitem' 	=> $result->restok->product->imagedir,
					'qty' 			=> $result->qty,
					'price' 		=> $result->unit_price_without_tax,
					'subtotal' 		=> $result->total_price_without_tax
				);

			}

			if($valueerror > 0){
				echo goResult(false, 'Maaf, order pembelian tidak ada');

				return;
			}
       		$data['gudang'] = GudangModel::where('status',1)->get();
			$data['commercialid'] 	= $commercial;
			
			$data['directory'] = 'https://maxipro.id/images/barang/';
			$data['matauang'] 		= MatauangModel::find($data['commercialid']->id_matauang);
			$data['supplier'] = SupplierModel::find($data['commercialid']->id_supplier);
			$data['allmatauang']	= MatauangModel::where('status','1')->get();
			$data['restok'] 		= $arrcommercialdetail;
			$data['gudang'] 		= GudangModel::where('status', '1')->asc()->get();
			$data['auth'] 			= true;
			$data['msg'] 			= 'success';
       
			echo toJson($data);
			return;
	}

	public function lcl_add_get(){
		$id = $this->input->get('id');

		if(!$id){
		
				$this->db->select('id, name, new_kode');

				$this->db->from('barang');

				$this->db->where('status_deleted', '0');

				$this->db->order_by('name', 'asc');

				$query = $this->db->get();

				$product = $query->result_array();
				
				$data['product'] = $product;

				$data['cabang'] = CabangModel::where('status',1)->get();

				$data['termin'] = TerminModel::where('status',1)->get();
				$data['matauang'] = MatauangModel::where('status',1)->get();

				$data['account'] = AccountModel::where('status',1)->get();
				echo json_encode($data);
				
				return;
		}
		else{
				$this->db->select('barang.id, barang.name, barang.new_kode, barang.image,barang_price.price_list');

				$this->db->from('barang');
				$this->db->join('barang_price','barang.id = barang_price.id_barang','inner');
				$this->db->where('barang.status_deleted', '0');

				$this->db->where('barang.id',$id);
				$this->db->where('barang_price.id_barang',$id);
				$this->db->order_by('barang.name', 'asc');

				$query = $this->db->get();

				$product = $query->result_array();
				
				$data['product'] = $product;
				$data['directory'] = 'https://maxipro.id/images/barang/';
				$data['cabang'] = CabangModel::where('status',1)->get();

				$data['termin'] = TerminModel::where('status',1)->get();
				$data['matauang'] = MatauangModel::where('status',1)->get();
				$data['gudang'] = GudangModel::where('status',1)->get();
				$data['account'] = AccountModel::where('status',1)->get();
				echo json_encode($data);

				return;
		}
	
	}
	public function lcl_select_barang_post(){
		$id = $this->input->post('id');
		if(!$id){
				$this->db->select('id, name, new_kode');

				$this->db->from('barang');

				$this->db->where('status_deleted', '0');

				$this->db->order_by('name', 'asc');

				$query = $this->db->get();

				$product = $query->result_array();
				
				$data['product'] = $product;

				$data['cabang'] = CabangModel::where('status',1)->get();

				$data['termin'] = TerminModel::where('status',1)->get();
				$data['matauang'] = MatauangModel::where('status',1)->get();

				$data['account'] = AccountModel::where('status',1)->get();
				echo json_encode($data);
				
				return;
		}
		else{
				$this->db->select('barang.id, barang.name, barang.new_kode, barang.image, barang.name_english, barang.name_china,barang.merk,barang.model,barang.long,barang.width,barang.height,barang.long_p,barang.width_p,barang.height_p,barang.cbm,barang.weight,barang.nett_weight,barang.spesification,barang_price.price_list');

				$this->db->from('barang');
				$this->db->join('barang_price','barang.id = barang_price.id_barang','inner');
				$this->db->where('barang.status_deleted', '0');

				$this->db->where_in('barang.id',$id);
				$this->db->where_in('barang_price.id_barang',$id);
				$this->db->order_by('barang.name', 'asc');

				$query = $this->db->get();

				$product = $query->result_array();
				
				$data['product'] = $product;
				$data['directory'] = 'https://maxipro.id/images/barang/';
				$data['cabang'] = CabangModel::where('status',1)->get();

				$data['termin'] = TerminModel::where('status',1)->get();
				$data['matauang'] = MatauangModel::where('status',1)->get();
				$data['gudang'] = GudangModel::where('status',1)->get();
				$data['account'] = AccountModel::where('status',1)->get();
				echo json_encode($data);

				return;
		}
	
	}

	public function select_ekspedisi_get(){
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }
		$data['select_ekspedisi'] = EkspedisiModel::where('status',1)->orderBy('name','asc')->get();
		$data['matauang'] = MatauangModel::where('status',1)->get();
		$data['auth'] 			= true;
		$data['msg'] 			= 'success';
		echo goResult(true,$data);
		return;

	}
	public function local_get() {
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }
		$tgl_awal = $this->input->get('tgl_awal');
		$tgl_akhir = $this->input->get('tgl_akhir');
		$checkdatevalue = $this->input->get('checkdatevalue') ?? 'unchecked';
		$invoiceFilter = $this->input->get('invoice') ?? '';
	
		// status check
		$request_check = $this->input->get('request_check') ?? 'requested';
		
	
		// Determine the start and end dates
		$lastDate = $tgl_akhir ?: date('Y-m-d');
		$startDate = $tgl_awal ?: PembelianlclModel::orderBy('tgl_transaksi', 'asc')->value('tgl_transaksi') ?: date('Y-m-d', strtotime($lastDate. '-7 days'));
	
		$status_arr = array_filter([$request_check]);
		
		// Build the query
		if($status_arr[0]!='all'){
			$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi','ekspedisilcl','matauang'])->where('invoice', 'like', '%' . $invoiceFilter . '%')
			
			->where('status_deleted', '0')
			->whereIn('status_process', $status_arr)
			->orderBy('id', 'desc');
	
		}
		else{
			$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi'])->where('invoice', 'like', '%' . $invoiceFilter . '%')
			
			->where('status_deleted', '0')
			
			->orderBy('id', 'desc');
			
		}	
		if ($checkdatevalue == 'checked') {
			$query->whereDate('tgl_transaksi', '>=', $startDate)
				  ->whereDate('tgl_transaksi', '<=', $lastDate);
		}
	
		$pembelianlcl = $query->get();
		
		// $id_pembelianlcl = $pembelianlcl->pluck('id')->toArray();
		

		// $pembelianlcl_detail = PembelianlcldetailModel::whereIn('id_pembelianlcl',$id_pembelianlcl)->orderBy('id_pembelianlcl','desc')->get();
		

		// $id_commercial_invoice = $pembelianlcl_detail->pluck('id_penjualanfromchina')->toArray();

		// // Jika $id_commercial_invoice kosong/null, isi dengan [0]
		// $id_commercial_invoice = !empty($id_commercial_invoice) ? $id_commercial_invoice : [0];

		
		$id_penjualanfromchina = [];

		foreach ($pembelianlcl as $pembelianlcl_loop) {
		    $temp_detail = [];
		    $has_only_null = true;

		    foreach ($pembelianlcl_loop['detail'] as $val_id) {
		        $id = $val_id->id_penjualanfromchina;

		        if (!is_null($id)) {
		            $has_only_null = false; // Ada nilai selain null
		            if (!in_array($id, $temp_detail, true)) {
		                $temp_detail[] = $id; // Masukkan nilai unik
		            }
		        }
		        else{}
		    }

		    // Jika hanya ada null dalam detail
		    if ($has_only_null) {
		        $id_penjualanfromchina[] = null;
		    } else {
		        // Masukkan nilai unik selain null
		        foreach ($temp_detail as $unique_id) {
		            $id_penjualanfromchina[] = $unique_id;
		        }
		    }
		}

		// dd(json_encode($id_penjualanfromchina));
		$penjualanfromchina = [];

		foreach ($id_penjualanfromchina as $id_id) {
		    if (is_null($id_id)) { // Periksa apakah nilai null
		        $penjualanfromchina[] = [
		        	'id' => 0,
		        	'invoice_no'=>0
		        ]; // Tambahkan nilai 0
		    } else {
		        $data2 = PenjualanfromchinaModel::find($id_id); // Ambil data dari model
		        if ($data2) {
		            $penjualanfromchina[] = $data2; // Tambahkan data jika ditemukan
		        }
		    }
		}

		// $penjualanfromchina_invoice = $penjualanfromchina->pluck('id','invoice_no')->toArray();

		$commercial 			= PenjualanfromchinaModel::where('status', 'requested')->where('status_deleted', '0')->desc()->get();
			
			$arrcommercial 			= array();
			foreach ($commercial as $key => $value) {
				if($value->mode_admin == '1'){
					$invoice 		= $value->invoice_no;
				}else{
					$invoice 		= 'INV-'.$value->invoice_no;
				}
				$arrcommercial[] 	= array(
					'id' 			=> $value->id,
					'id_supplier' 	=> $value->id_supplier,
					'invoice' 		=> $invoice,
					'name' 			=> $value->name,
					'supplier' 		=> $value->supplier->name
				);
			}

			// $supplier 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('id_supplier', '!=', 0)->where('status', 'requested')->groupBy('id_supplier')->get();
			$supplier               =SupplierModel::where('status','!=',0)->get();
		
			$arrsupplier 			= array();
			foreach ($supplier as $key => $value) {
				$arrsupplier[] 		= array(
					'id' 			=> $value->id,
					'name' 			=> $value->name
				);
			}
			
			array_multisort( array_column($arrsupplier, "name"), SORT_ASC, $arrsupplier);
			// $data['suppliercommercial'] = $arrsupplier;
		$penjualan 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('status', 'requested')->orderBy('date', 'desc')->get();
		
		$idPenjualan 				= array();

		foreach ($penjualan as $key => $value) {

			$idPenjualan[] 			= $value->id;

		}



		

		$total						= count($idPenjualan);

	

		$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang'])
	
		->whereIn('penjualanfromchina.id', $idPenjualan)
		->where('penjualanfromchina.category_comercial_invoice','local')
		->orderBy('penjualanfromchina.invoice_no', 'desc')
		;


		$totalRowsPenjualan = $penjualanQuery->count();

		$comercialInvoice = $penjualanQuery->get();
	
		// $account = AccountModel::where('status',1)->orderBy('name')->get();
		$account = AccountModel::where('status', 1)
                       ->orderByRaw("CASE WHEN name = 'Rekening PT' THEN 0 ELSE 1 END")
                       ->orderBy('name', 'asc')
                       ->get();

		$termin = TerminModel::where('status',1)->get();
		$data_arr = [];
		foreach ($pembelianlcl as $key => $val) {
			if(count($val->ekspedisilcl) >0){
			
				$data_arr[] = $val['ekspedisilcl'][0]['id_ekspedisi'];
			}
		}
		$ekspedisi = EkspedisiModel::whereIn('id',$data_arr)->get();
		$select_ekspedisi = EkspedisiModel::where('status',1)->orderBy('name','asc')->get();
		$matauang = MatauangModel::where('status',1)->get();
		$data = [
			'pembelianlcl' => $pembelianlcl,
			// 'id_pembelianlcl'=>$id_pembelianlcl,
			// 'pembelianlcl_detail'=>$pembelianlcl_detail,
			'id_commercial_invoice'=>$id_penjualanfromchina,
			// 'penjualanfromchina'=>$penjualanfromchina,
			'invoice'=>$penjualanfromchina,
			
			'ekspedisi' => $ekspedisi,
			'select_ekspedisi'=>$select_ekspedisi,
			'invoiceFilter' => $invoiceFilter,
			'tgl_awal' => $startDate,
			'tgl_akhir' => $lastDate,
			'checkdatevalue' => $checkdatevalue,
			'request_check' => $request_check,
			'supplier' => $arrsupplier,
			'penjualan' =>$comercialInvoice,
			'account'=> $account,
			'termin'=> $termin,
			'matauang'=> $matauang,
	
		];
		
		
		echo goResult(true, $data);
		return;
	}
	public function local_editview_get($invoice){
			
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			
			$data['pembelianlcl'] = PembelianlclModel::where('invoice', $invoice)->first();
			
			$details = $data['pembelianlcl']->detail;
			$data['matauang'] = MatauangModel::where('id',$data['pembelianlcl']->id_matauang)->get();
			$data['gudang'] = GudangModel::where('status',1)->get();

			$id_barang=[];
			
				foreach ($details as $value) {
					if(isset($value['id_barang'])){
						$id_barang[] =$value['id_barang'];

					}
				}
				$data['image_barang'] = BarangModel::whereIn('id', $id_barang)
                                   ->select('id', 'name', 'image')
                                   ->get();
			
			
			if($data['pembelianlcl']){
				$arrdetaillcl 			= array();
				foreach ($data['pembelianlcl']->detail as $key => $value) {
					if($value->id_penjualanfromchina != NULL || $value->id_penjualanfromchina != ''){
						$arrdetaillcl[] 	= $value->id;
					}
				}

				$detaillcl 				= PembelianlcldetailModel::whereIn('id', $arrdetaillcl)->groupBy('id_penjualanfromchina')->desc()->get();
				$idcommercialcheck 		= array();
				foreach ($detaillcl as $key => $value) {
					$idcommercialcheck[] = $value->id_penjualanfromchina;
				}

				$commercialcheck 		= PenjualanfromchinaModel::whereIn('id', $idcommercialcheck)->where('status_deleted', '0')->desc()->get();
				$arrcommercialcheck 	= array();
				foreach ($commercialcheck as $key => $value) {
					if($value->mode_admin == '1'){
						$invoice 		= $value->invoice_no;
					}else{
						$invoice 		= 'INV-'.$value->invoice_no;
					}

					$arrcommercialcheck[] 	= array(
						'id' 			=> $value->id,
						'id_supplier' 	=> $value->id_supplier,
						'invoice' 		=> $invoice,
						'name' 			=> $value->name,
						'supplier' 		=> $value->supplier->name
					);
				}

				$data['commercialcheck'] = $arrcommercialcheck;
			}

			$data['ekspedisilcl'] 	= PembelianlclekspedisiModel::where('id_pembelianlcl', $data['pembelianlcl']->id)->asc()->get();
			
			$id_ekspedisi_lcl = [];
			foreach ($data['ekspedisilcl'] as $value) {
		
				$id_ekspedisi_lcl[] = $value->id_ekspedisi;
			}
		
				// $data['nama_ekspedisi'] = EkspedisiModel::whereIn('id', $id_ekspedisi_lcl)
			 //    ->orderByRaw('FIELD(id, '.implode(',', $id_ekspedisi_lcl).')')
			 //    ->get();
			$id_ekspedisi_lcl = array_filter($id_ekspedisi_lcl); // Filter out any empty values

			if (!empty($id_ekspedisi_lcl)) {
			    $nama_ekspedisi = EkspedisiModel::whereIn('id', $id_ekspedisi_lcl)
			       ->orderByRaw('FIELD(id, ' . implode(',', $id_ekspedisi_lcl) . ')')
			        ->get()
			        ->keyBy('id');

			} else {
			    $nama_ekspedisi = collect(); // Return an empty collection if no IDs are provided
			}
			
			$data['nama_ekspedisi'] = collect($id_ekspedisi_lcl)->map(function($id) use ($nama_ekspedisi) {
			    return $nama_ekspedisi->get($id); // Ambil data sesuai ID meskipun ada duplikat
			});


		    $id_matauang_ekspedisi = [];
		    foreach ($data['ekspedisilcl'] as $key => $value) {
		    	// dd(json_encode($value));
		    	$id_matauang_ekspedisi[] = $value->id_matauang;
		    }
		    
		    // Ambil data unik dari database berdasarkan id_matauang_ekspedisi
			$id_matauang_ekspedisi = array_filter($id_matauang_ekspedisi); // Filter out any empty values

			if (!empty($id_matauang_ekspedisi)) {
			    $matauang = MatauangModel::whereIn('id', $id_matauang_ekspedisi)
			        ->orderByRaw('FIELD(id, ' . implode(',', $id_matauang_ekspedisi) . ')')
			        ->get()
			        ->keyBy('id');
			} else {
			    $matauang = collect(); // Return an empty collection if no IDs are provided
			}


			// Buat array hasil dengan mengulang sesuai jumlah id di $id_matauang_ekspedisi
			$data['matauang_ekspedisi'] = collect($id_matauang_ekspedisi)->map(function($id) use ($matauang) {
			    return $matauang->get($id); // Ambil data sesuai ID meskipun ada duplikat
			});

			$data['pembayaranlcl'] 	= PembelianlclbayarModel::where('id_pembelianlcl', $data['pembelianlcl']->id)->asc()->get();
			$commercial 			= PenjualanfromchinaModel::where('status', 'requested')->desc()->get();
			$arrcommercial 			= array();
			foreach ($commercial as $key => $value) {
				if($value->mode_admin == '1'){
					$invoice 		= $value->invoice_no;
				}else{
					$invoice 		= 'INV-'.$value->invoice_no;
				}

				$arrcommercial[] 	= array(
					'id' 			=> $value->id,
					'id_supplier' 	=> $value->id_supplier,
					'invoice' 		=> $invoice,
					'name' 			=> $value->name,
					'supplier' 		=> $value->supplier->name
				);
			}

			$supplier 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('id_supplier', '!=', 0)->where('status', 'requested')->groupBy('id_supplier')->get();
			$arrsupplier 			= array();
			foreach ($supplier as $key => $value) {
				$arrsupplier[] 		= array(
					'id' 			=> $value->id_supplier,
					'name' 			=> $value->supplier->name
				);
			}

			$arrsupplier[] 		= array(
				'id' 			=> $data['pembelianlcl']->id_supplier,
				'name' 			=> $data['pembelianlcl']->supplier->name
			);

			array_multisort( array_column($arrsupplier, "name"), SORT_ASC, $arrsupplier);

			$data['suppliercommercial'] = $arrsupplier;
			$data['commercial'] 		= $arrcommercial;
			$penerimaan 		= PenerimaanpembelianModel::where('status', '1')->where('category', 'lcl')->where('id_fcl_lcl', $data['pembelianlcl']->id)->desc()->first();
			if($penerimaan){
				$data['statuspenerimaan'] 	= 'true';
				$data['penerimaan'] 		= $penerimaan;
			}else{
				$data['statuspenerimaan'] 	= 'false';
				$data['penerimaan'] 		= '';
			}

			echo goResult(true,$data);
			return;
	}

	public function lcl_get() {
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }
		$tgl_awal = $this->input->get('tgl_awal');
		$tgl_akhir = $this->input->get('tgl_akhir');
		$checkdatevalue = $this->input->get('checkdatevalue') ?? 'unchecked';
		$invoiceFilter = $this->input->get('invoice') ?? '';
		$idbarang = $this->input->get('barang') ?? '';
		$idekspedisi = $this->input->get('ekspedisi') ?? '';

		$invoiceFilterNew = PembelianlclModel::Where('invoice',$invoiceFilter)->get();
		$barangfilter = PembelianlcldetailModel::Where('id_barang',$idbarang)->get();
		$ekspedisifilter = PembelianlclekspedisiModel::where('id_ekspedisi',$idekspedisi)->get();
		// dd(json_encode($invoiceFilterNew));

		$idpembelianlclfilter = [];

		if ($barangfilter->isEmpty() && $ekspedisifilter->isEmpty() && $invoiceFilterNew->isEmpty()) {
			 	$idpembelianlclfilter = '';
		}
		else{
				foreach ($invoiceFilterNew as $key_invoice => $value_invoice) {
					$idpembelianlclfilter[] = $value_invoice->id;
				}

				foreach ($barangfilter as $key_barang => $value) {
					$idpembelianlclfilter[] = $value->id_pembelianlcl;
				}
				foreach ($ekspedisifilter as $key_ekspedisi => $value_ekspedisi) {
					$idpembelianlclfilter[] = $value_ekspedisi->id_pembelianlcl;
				}
				$idpembelianlclfilter = array_unique($idpembelianlclfilter);

				// Jika perlu re-index array setelah array_unique
				$idpembelianlclfilter = array_values($idpembelianlclfilter);
		}
			// dd($idpembelianlclfilter);
		
		
		// status check
		$request_check = $this->input->get('request_check') ?? 'requested';
		
	
		// Determine the start and end dates
		$lastDate = $tgl_akhir ?: date('Y-m-d');
		$startDate = $tgl_awal ?: PembelianlclModel::orderBy('tgl_transaksi', 'asc')->value('tgl_transaksi') ?: date('Y-m-d', strtotime($lastDate. '-7 days'));
	
		$status_arr = array_filter([$request_check]);
		
		// Build the query
		if($status_arr[0]!='all'){
			if(!empty($idpembelianlclfilter)){
				$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi','ekspedisilcl','matauang'])
			
				->whereIn('id',$idpembelianlclfilter)
				->where('status_deleted', '0')
				->whereIn('status_process', $status_arr)
				->orderBy('id', 'desc');	
			}
			else{
				if(!empty($idpembelianlclfilter)){
					$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi','ekspedisilcl','matauang'])
					->whereIn('id',$idpembelianlclfilter)
					->where('status_deleted', '0')
					->whereIn('status_process', $status_arr)
					->orderBy('id', 'desc');			
				}
				else{
					$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi','ekspedisilcl','matauang'])
			
					->where('status_deleted', '0')
					->whereIn('status_process', $status_arr)
					->orderBy('id', 'desc');			
				}
				
			}
	
		}
		else{
			$query = PembelianlclModel::with(['detail','supplier','cabang','teknisi'])
			
			->whereIn('id',$idpembelianlclfilter)
			->where('status_deleted', '0')
			
			->orderBy('id', 'desc');
			
		}	
		if ($checkdatevalue == 'checked') {
			$query->whereDate('tgl_transaksi', '>=', $startDate)
				  ->whereDate('tgl_transaksi', '<=', $lastDate);
		}
	
	
		$pembelianlcl = $query->get();

		$id_penjualanfromchina = [];

		foreach ($pembelianlcl as $pembelianlcl_loop) {
		    $temp_detail = [];
		    $has_only_null = true;

		    foreach ($pembelianlcl_loop['detail'] as $val_id) {
		        $id = $val_id->id_penjualanfromchina;

		        if (!is_null($id)) {
		            $has_only_null = false; // Ada nilai selain null
		            if (!in_array($id, $temp_detail, true)) {
		                $temp_detail[] = $id; // Masukkan nilai unik
		            }
		        }
		        else{}
		    }

		    // Jika hanya ada null dalam detail
		    if ($has_only_null) {
		        $id_penjualanfromchina[] = null;
		    } else {
		        // Masukkan nilai unik selain null
		        foreach ($temp_detail as $unique_id) {
		            $id_penjualanfromchina[] = $unique_id;
		        }
		    }
		}

		
		$penjualanfromchina = [];

		foreach ($id_penjualanfromchina as $id_id) {
		    if (is_null($id_id)) { // Periksa apakah nilai null
		        $penjualanfromchina[] = [
		        	'id' => 0,
		        	'invoice_no'=>0
		        ]; // Tambahkan nilai 0
		    } else {
		        $data2 = PenjualanfromchinaModel::find($id_id); // Ambil data dari model
		        if ($data2) {
		            $penjualanfromchina[] = $data2; // Tambahkan data jika ditemukan
		        }
		    }
		}

		

		$commercial 			= PenjualanfromchinaModel::where('status', 'requested')->where('status_deleted', '0')->desc()->get();
			
			$arrcommercial 			= array();
			foreach ($commercial as $key => $value) {
				if($value->mode_admin == '1'){
					$invoice 		= $value->invoice_no;
				}else{
					$invoice 		= 'INV-'.$value->invoice_no;
				}
				$arrcommercial[] 	= array(
					'id' 			=> $value->id,
					'id_supplier' 	=> $value->id_supplier,
					'invoice' 		=> $invoice,
					'name' 			=> $value->name,
					'supplier' 		=> $value->supplier->name
				);
			}

			
			$supplier               =SupplierModel::where('status','!=',0)->get();
		
			$arrsupplier 			= array();
			foreach ($supplier as $key => $value) {
				$arrsupplier[] 		= array(
					'id' 			=> $value->id,
					'name' 			=> $value->name
				);
			}
			
			array_multisort( array_column($arrsupplier, "name"), SORT_ASC, $arrsupplier);
			
			$penjualan 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('status', 'requested')->orderBy('date', 'desc')->get();
			
			$idPenjualan 				= array();

			foreach ($penjualan as $key => $value) {

				$idPenjualan[] 			= $value->id;

			}



		

			$total						= count($idPenjualan);

	

			$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang'])
		
			->whereIn('penjualanfromchina.id', $idPenjualan)
			->where('penjualanfromchina.category_comercial_invoice','lcl')
			->orderBy('penjualanfromchina.invoice_no', 'desc')
			;


			$totalRowsPenjualan = $penjualanQuery->count();

			$comercialInvoice = $penjualanQuery->get();
		
			// $account = AccountModel::where('status',1)->orderBy('name')->get();
			$account = AccountModel::where('status', 1)
	                       ->orderByRaw("CASE WHEN name = 'Rekening PT' THEN 0 ELSE 1 END")
	                       ->orderBy('name', 'asc')
	                       ->get();

			$termin = TerminModel::where('status',1)->get();
			$data_arr = [];
			foreach ($pembelianlcl as $key => $val) {
				if(count($val->ekspedisilcl) >0){
				
					$data_arr[] = $val['ekspedisilcl'][0]['id_ekspedisi'];
				}
			}
			$ekspedisi = EkspedisiModel::whereIn('id',$data_arr)->get();
			$select_ekspedisi = EkspedisiModel::where('status',1)->orderBy('name','asc')->get();
			$matauang = MatauangModel::where('status',1)->get();
			$this->db->select('id, new_kode, name'); // Pastikan kolom yang diambil benar
			$this->db->from('barang');
			$this->db->where('status_deleted', '0');
			$this->db->order_by('new_kode', 'asc');

			$query_barang = $this->db->get(); // Eksekusi query

			$barang = $query_barang->result_array();

			$data = [
				'pembelianlcl' => $pembelianlcl,
				'id_commercial_invoice'=>$id_penjualanfromchina,
				'invoice'=>$penjualanfromchina,
				'ekspedisi' => $ekspedisi,
				'select_ekspedisi'=>$select_ekspedisi,
				'invoiceFilter' => $invoiceFilter,
				'tgl_awal' => $startDate,
				'tgl_akhir' => $lastDate,
				'checkdatevalue' => $checkdatevalue,
				'request_check' => $request_check,
				'supplier' => $arrsupplier,
				'penjualan' =>$comercialInvoice,
				'account'=> $account,
				'termin'=> $termin,
				'matauang'=> $matauang,
				'barang'=> $barang,
				'id_barang'=>$idbarang,
				'id_ekspedisi'=>$idekspedisi,
		
			];
			
			
			echo goResult(true, $data);
			return;
	}
	public function lcl_editview_get($invoice){
			
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			
			$data['pembelianlcl'] = PembelianlclModel::with('teknisi','cabang')->where('invoice', $invoice)->first();
		
			$details = $data['pembelianlcl']->detail;
			$data['matauang'] = MatauangModel::where('id',$data['pembelianlcl']->id_matauang)->get();
			$data['gudang'] = GudangModel::where('status',1)->get();

			$id_barang=[];
			
				foreach ($details as $value) {
					if(isset($value['id_barang'])){
						$id_barang[] =$value['id_barang'];

					}
				}
				// dd($id_barang);
				$data['image_barang'] = BarangModel::select('id', 'name', 'image', 'new_kode')
			    ->whereIn('id', $id_barang)
			    ->get()
			    ->map(function ($item) {
			        return [
			            'id' => $item->id,
			            'name' => $item->name,
			            'image' => $item->image,
			            'new_kode' => $item->new_kode,
			        ];
			    });

				// dd(json_encode($data));
			
			if($data['pembelianlcl']){
				$arrdetaillcl 			= array();
				foreach ($data['pembelianlcl']->detail as $key => $value) {
					if($value->id_penjualanfromchina != NULL || $value->id_penjualanfromchina != ''){
						$arrdetaillcl[] 	= $value->id;
					}
				}

				$detaillcl 				= PembelianlcldetailModel::whereIn('id', $arrdetaillcl)->groupBy('id_penjualanfromchina')->desc()->get();
				$idcommercialcheck 		= array();
				foreach ($detaillcl as $key => $value) {
					$idcommercialcheck[] = $value->id_penjualanfromchina;
				}

				$commercialcheck 		= PenjualanfromchinaModel::whereIn('id', $idcommercialcheck)->where('status_deleted', '0')->desc()->get();
				$arrcommercialcheck 	= array();
				foreach ($commercialcheck as $key => $value) {
					if($value->mode_admin == '1'){
						$invoice 		= $value->invoice_no;
					}else{
						$invoice 		= 'INV-'.$value->invoice_no;
					}

					$arrcommercialcheck[] 	= array(
						'id' 			=> $value->id,
						'id_supplier' 	=> $value->id_supplier,
						'invoice' 		=> $invoice,
						'name' 			=> $value->name,
						'supplier' 		=> $value->supplier->name
					);
				}

				$data['commercialcheck'] = $arrcommercialcheck;
			}

			$data['ekspedisilcl'] 	= PembelianlclekspedisiModel::where('id_pembelianlcl', $data['pembelianlcl']->id)->asc()->get();
			
			$id_ekspedisi_lcl = [];
			foreach ($data['ekspedisilcl'] as $value) {
		
				$id_ekspedisi_lcl[] = $value->id_ekspedisi;
			}
		
				// $data['nama_ekspedisi'] = EkspedisiModel::whereIn('id', $id_ekspedisi_lcl)
			 //    ->orderByRaw('FIELD(id, '.implode(',', $id_ekspedisi_lcl).')')
			 //    ->get();
			$id_ekspedisi_lcl = array_filter($id_ekspedisi_lcl); // Filter out any empty values

			if (!empty($id_ekspedisi_lcl)) {
			    $nama_ekspedisi = EkspedisiModel::whereIn('id', $id_ekspedisi_lcl)
			       ->orderByRaw('FIELD(id, ' . implode(',', $id_ekspedisi_lcl) . ')')
			        ->get()
			        ->keyBy('id');

			} else {
			    $nama_ekspedisi = collect(); // Return an empty collection if no IDs are provided
			}
			
			$data['nama_ekspedisi'] = collect($id_ekspedisi_lcl)->map(function($id) use ($nama_ekspedisi) {
			    return $nama_ekspedisi->get($id); // Ambil data sesuai ID meskipun ada duplikat
			});


		    $id_matauang_ekspedisi = [];
		    foreach ($data['ekspedisilcl'] as $key => $value) {
		    	// dd(json_encode($value));
		    	$id_matauang_ekspedisi[] = $value->id_matauang;
		    }
		    
		    // Ambil data unik dari database berdasarkan id_matauang_ekspedisi
			$id_matauang_ekspedisi = array_filter($id_matauang_ekspedisi); // Filter out any empty values

			if (!empty($id_matauang_ekspedisi)) {
			    $matauang = MatauangModel::whereIn('id', $id_matauang_ekspedisi)
			        ->orderByRaw('FIELD(id, ' . implode(',', $id_matauang_ekspedisi) . ')')
			        ->get()
			        ->keyBy('id');
			} else {
			    $matauang = collect(); // Return an empty collection if no IDs are provided
			}


			// Buat array hasil dengan mengulang sesuai jumlah id di $id_matauang_ekspedisi
			$data['matauang_ekspedisi'] = collect($id_matauang_ekspedisi)->map(function($id) use ($matauang) {
			    return $matauang->get($id); // Ambil data sesuai ID meskipun ada duplikat
			});

			$data['pembayaranlcl'] 	= PembelianlclbayarModel::where('id_pembelianlcl', $data['pembelianlcl']->id)->asc()->get();
			$commercial 			= PenjualanfromchinaModel::where('status', 'requested')->desc()->get();
			$arrcommercial 			= array();
			foreach ($commercial as $key => $value) {
				if($value->mode_admin == '1'){
					$invoice 		= $value->invoice_no;
				}else{
					$invoice 		= 'INV-'.$value->invoice_no;
				}

				$arrcommercial[] 	= array(
					'id' 			=> $value->id,
					'id_supplier' 	=> $value->id_supplier,
					'invoice' 		=> $invoice,
					'name' 			=> $value->name,
					'supplier' 		=> $value->supplier->name
				);
			}

			$supplier 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('id_supplier', '!=', 0)->where('status', 'requested')->groupBy('id_supplier')->get();
			$arrsupplier 			= array();
			foreach ($supplier as $key => $value) {
				$arrsupplier[] 		= array(
					'id' 			=> $value->id_supplier,
					'name' 			=> $value->supplier->name
				);
			}

			$arrsupplier[] 		= array(
				'id' 			=> $data['pembelianlcl']->id_supplier,
				'name' 			=> $data['pembelianlcl']->supplier->name
			);

			array_multisort( array_column($arrsupplier, "name"), SORT_ASC, $arrsupplier);
	
			$data['suppliercommercial'] = $arrsupplier;
			$data['commercial'] 		= $arrcommercial;
			$data['matauang_all']		= MatauangModel::where('status',1)->get();
			$penerimaan 		= PenerimaanpembelianModel::where('status', '1')->where('category', 'lcl')->where('id_fcl_lcl', $data['pembelianlcl']->id)->desc()->first();
			if($penerimaan){
				$data['statuspenerimaan'] 	= 'true';
				$data['penerimaan'] 		= $penerimaan;
			}else{
				$data['statuspenerimaan'] 	= 'false';
				$data['penerimaan'] 		= '';
			}

			echo goResult(true,$data);
			return;
	}

	//menambahkan ekspedisi di row tabel ekspedisi
	public function lcl_createekspedisi_post(){
		 $teknisi = $this->validate_teknisi_id();
	     if (!$teknisi) {
	            return; // Stop further execution if validation fails
	     }
		 $rules      = [
                'required'  => [
                    ['tgl_kirim'],['matauang'],['price'],['rute'],['ekspedisi'],['resi']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $str = "";
            for($i = 0; $i < 1; $i++){
                $characters = array_merge(range('0','9'));
                $max = count($characters) - 1;
                for ($j = 0; $j < 5; $j++) {
                    $rand = mt_rand(0, $max);
                    $str .= $characters[$rand];
                }

                $kodeOld            = PembelianlclekspedisiModel::where('kode', $str)->get();
                if(count($kodeOld) > 0){
                    $str    = "";
                    $i      = 0;
                }
            }

            $matauang               = MatauangModel::find($this->input->post('matauang'));
            $rute                   = $this->input->post('rute');
            if($rute == 'localchina'){
                $namerute           = 'Lokal China';
            }elseif($rute == 'chinaindo'){
                $namerute           = 'China Indo';
            }else{
                $namerute           = 'Lokal Indo';
            }

            $ekspedisi              = EkspedisiModel::find($this->input->post('ekspedisi'));
            $data['kodepengiriman'] = 'EKS'.$str;
            $data['tgl_kirim']      = $this->input->post('tgl_kirim');
            $data['tgl_kirim_name'] = tgl_indo($this->input->post('tgl_kirim'));
            $data['matauang']       = $matauang->id;
            $data['matauang_kode']  = $matauang->kode;
            $data['matauang_name']  = $matauang->name;
            $data['matauang_simbol'] = $matauang->simbol;
            $data['price']          = $this->input->post('price');
            $data['price_name']     = number_format($this->input->post('price'));
            $data['rute']           = $rute;
            $data['rute_name']      = $namerute;
            $data['ekspedisi']      = $ekspedisi->id;
            $data['ekspedisi_name'] = $ekspedisi->name;
            $data['resi']           = $this->input->post('resi');
            $data['keterangan']     = $this->input->post('keterangan');
            $data['auth']           = true;
            $data['msg']            = 'success';

            echo toJson($data);
            return;
	}
	
	//mengupdate row ekspedisi di tabel
	public function lcl_editekspedisi_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$rules      = [
                'required'  => [
                    ['tgl_kirim_update'],['matauang_update'],['price_update'],['rute_update'],['ekspedisi_update'],['resi_update']
                ]
            ];
            
            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $matauang               = MatauangModel::find($this->input->post('matauang_update'));
            $rute                   = $this->input->post('rute_update');
            if($rute == 'localchina'){
                $namerute           = 'Lokal China';
            }elseif($rute == 'chinaindo'){
                $namerute           = 'China Indo';
            }else{
                $namerute           = 'Lokal Indo';
            }

            $ekspedisi              = EkspedisiModel::find($this->input->post('ekspedisi_update'));
            $data['kodepengiriman'] = $this->input->post('kodepengiriman_update');
            $data['tgl_kirim']      = $this->input->post('tgl_kirim_update');
            $data['tgl_kirim_name'] = tgl_indo($this->input->post('tgl_kirim_update'));
            $data['matauang']       = $matauang->id;
            $data['matauang_kode']  = $matauang->kode;
            $data['matauang_name']  = $matauang->name;
            $data['matauang_simbol'] = $matauang->simbol;
            $data['price']          = $this->input->post('price_update');
            $data['price_name']     = number_format($this->input->post('price_update'));
            $data['rute']           = $rute;
            $data['rute_name']      = $namerute;
            $data['ekspedisi']      = $ekspedisi->id;
            $data['ekspedisi_name'] = $ekspedisi->name;
            $data['resi']           = $this->input->post('resi_update');
            $data['keterangan']     = $this->input->post('keterangan_update');
            $data['auth']           = true;
            $data['msg']            = 'success';

            echo toJson($data);
            return;
	}

	public function lcl_saveekspedisi_post(){
		$teknisi = $this->validate_teknisi_id();
        if (!$teknisi) {
            return; // Stop further execution if validation fails
        }
		 $id = $this->input->post('id_lcl');
		 $pembelian  = PembelianlclModel::find($id);
            if(!$pembelian){
                echo goResult(false, 'Pembelian LCL tidak ada');
                return;
            }

            $ekspedisi      = $this->input->post('kodepengiriman_ekspedisi');
            if($ekspedisi){
                PembelianlclekspedisiModel::where('id_pembelianlcl', $id)->delete();
                for ($i=0; $i < count($ekspedisi); $i++) {
                    $newekspedisi                   = new PembelianlclekspedisiModel;
                    $newekspedisi->id_pembelianlcl  = $pembelian->id;
                    $newekspedisi->id_ekspedisi     = $this->input->post('ekspedisi_ekspedisi')[$i];
                    $newekspedisi->id_matauang      = $this->input->post('matauang_ekspedisi')[$i];
                    $newekspedisi->kode             = $this->input->post('kodepengiriman_ekspedisi')[$i];
                    $newekspedisi->tgl_kirim        = $this->input->post('tgl_kirim_ekspedisi')[$i];
                    $newekspedisi->rute             = $this->input->post('rute_ekspedisi')[$i];
                    $newekspedisi->price            = $this->input->post('price_ekspedisi')[$i];
                    $newekspedisi->resi             = $this->input->post('resi_ekspedisi')[$i];
                    $newekspedisi->keterangan       = $this->input->post('keterangan_ekspedisi')[$i];
                    $newekspedisi->status           = '1';
                    $newekspedisi->save();
                }

                echo goResult(true, 'Ekspedisi berhasil diupdate');
                return;
            }else{
                echo goResult(false, 'Ekspedisi belum di isikan');
                return;
            }
	}
	public function lcl_ekspedisi_delete($id){
		 	$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$ekspedisilcl       = PembelianlclekspedisiModel::where('kode', $id)->first();
            if(!$ekspedisilcl){
                echo goResult(false, 'Ekspedisi not found');
                return;
            }

            PembelianlclekspedisiModel::find($ekspedisilcl->id)->delete();
            echo goResult(true, 'Deleted success');
            return;
	}
	public function lcl_edit_post(){
		  $teknisi = $this->validate_teknisi_id();
	      if (!$teknisi) {
	            return; // Stop further execution if validation fails
	      }
		  $id = $this->input->post('id_lcl');
		  $pembelian  = PembelianlclModel::find($id);
            if(!$pembelian){
                echo goResult(false, 'Pembelian LCL tidak ada');
                return;
            }

            $rules      = [
                'required'  => [
                    ['database'],['tgl_transaksi'],['termin'],['account'],['supplier'],['matauang'],['cabang']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            //convert
            if($this->input->post('statusconvert') == ''){
                $statusconvert  = 0;
            }else{
                $statusconvert  = $this->input->post('statusconvert');
            }
            $nominalconvert     = $this->input->post('valuenominalconvert');
            $categoryconvert    = $this->input->post('valuecategoryconvert');
            $matauangid     = MatauangModel::find($this->input->post('matauang_asal'));
            $iditem         = $this->input->post('iditem');
            $idrestok       = $this->input->post('idrestok');
            $idcommercial   = $this->input->post('idcommercial');
            $item           = $this->input->post('item');

            if($this->input->post('matauang_asal') != 1){ //Jika Asal Sudah IDR
                $priceasal  = $this->input->post('price_asal');
            }

            $price          = $this->input->post('price'); //IDR
            $qty            = $this->input->post('qty');
            $disc           = $this->input->post('disc');
            $subtotal       = $this->input->post('subtotal');
            $ppn            = $this->input->post('ppn_item');
            $priceppn       = $this->input->post('priceppn');
            $gudang         = $this->input->post('gudang');
            $itemnull = 0;
            for ($i=0; $i < count($item); $i++) { 
                if($item[$i] == ''){
                    $itemnull = $itemnull + 1;
                }
            }

            $gudangnull = 0;
            for ($i=0; $i < count($gudang); $i++) { 
                if($gudang[$i] == ''){
                    $gudangnull = $gudangnull + 1;
                }
            }

            $qtynull =  0;
            for ($i=0; $i < count($qty); $i++) { 
                if($qty[$i] == ''){
                    $qtynull = $qtynull + 1;
                }
                if($qty[$i] == 0){
                    $qtynull = $qtynull + 1;
                }
            }

            if($itemnull > 0){
                echo goResult(false, 'Item is required');
                return;
            }

            if($qtynull > 0){
                echo goResult(false, 'Qty is required');
                return;
            }

            if($gudangnull > 0){
                echo goResult(false, 'Gudang is required');
                return;
            }
            if($this->input->post('matauang_asal') == 1){
                //Asal IDR
                $pricenull =    0;
                for ($i=0; $i < count($price); $i++) { 
                    if($price[$i] == ''){
                        $pricenull = $pricenull + 1;
                    }

                    if($price[$i] == 0){
                        $pricenull = $pricenull + 1;
                    }
                }

                if($pricenull > 0){
                    echo goResult(false, 'Price is required');
                    return;
                }
            }else{
                //Asal Not IDR
                $priceasalnull =    0;
                for ($i=0; $i < count($priceasal); $i++) { 
                    if($priceasal[$i] == ''){
                        $priceasalnull = $priceasalnull + 1;
                    }

                    if($priceasal[$i] == 0){
                        $priceasalnull = $priceasalnull + 1;
                    }
                }

                if($priceasalnull > 0){
                    echo goResult(false, 'Price asal is required');
                    return;
                }
                
                //IDR
                $pricenull =    0;
                for ($i=0; $i < count($price); $i++) { 
                    if($price[$i] == ''){
                        $pricenull = $pricenull + 1;
                    }

                    if($price[$i] == 0){
                        $pricenull = $pricenull + 1;
                    }
                }
                
                if($pricenull > 0){
                    echo goResult(false, 'Price IDR is required');
                    return;
                }
                
                if($statusconvert == 0){
                    echo goResult(false, 'Data required convert to IDR');
                    return;
                }
            }
               if($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '1'){
                $statusppn  = '1';
                $includeppn = '1';
            }elseif($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '0'){
                $statusppn  = '1';
                $includeppn = '0';
            }elseif($this->input->post('ppn') == '0' && $this->input->post('includeppn') == '1'){
                $statusppn  = '1';
                $includeppn = '1';
            }else{
                $statusppn  = '0';
                $includeppn = '0';
            }
              if($this->input->post('matauang_asal') != 1){
                $subtotalallasal        = $this->input->post('td_total') / $nominalconvert;
                $subtotalall            = $this->input->post('td_total'); //IDR
            }else{
                $subtotalallasal        = $this->input->post('td_total');
                $subtotalall            = $this->input->post('td_total'); //IDR
            }
            $pembelian->noreferensi       = $this->input->post('noreferensi');
            $pembelian->tgl_transaksi       = $this->input->post('tgl_transaksi');
            $pembelian->name_db             = $this->input->post('database');
            $pembelian->id_termin           = $this->input->post('termin');
            $pembelian->id_account          = $this->input->post('account');
            $pembelian->id_matauang         = $this->input->post('matauang_asal');
            $pembelian->id_cabang           = $this->input->post('cabang');
            $pembelian->id_supplier         = $this->input->post('supplier');
            $pembelian->ppn                 = $this->input->post('td_ppn');
            $pembelian->discount            = $this->input->post('td_discount');
            $pembelian->discount_nominal    = $this->input->post('td_discount_nominal');
            $pembelian->keterangan          = $this->input->post('keterangan');
            $pembelian->status_ppn          = $statusppn;
            $pembelian->status_includeppn   = $includeppn;
            $pembelian->subtotal            = $subtotalallasal;
            $pembelian->subtotal_idr        = $subtotalall;
            $pembelian->status_converttorupiah = $statusconvert;
            $pembelian->nominal_convert     = $nominalconvert;
            $pembelian->category_convert    = $categoryconvert;
            if($pembelian->save()){
                $oldcommercial              = PembelianlcldetailModel::where('id_pembelianlcl', $id)->get();
                
                foreach ($oldcommercial as $key => $value) {
                	if($value->id_penjualanfromchina){
                		$commercialinvoice              = PenjualanfromchinaModel::find($value->id_penjualanfromchina);
                    	
                    	
	                    $commercialinvoice->status      = 'requested';
	                    
	                    $commercialinvoice->save();
                	}
                    

                }

                PembelianlcldetailModel::where('id_pembelianlcl', $id)->delete();
                for ($i=0; $i < count($item); $i++) {
                    if($this->input->post('matauang_asal') != 1){
                        $subtotalconvert    = $subtotal[$i] / $nominalconvert;
                    }else{
                        $subtotalconvert    = $subtotal[$i];
                    }

                    if($this->input->post('matauang_asal') != 1){
                        $priceasalvalue     = $priceasal[$i];
                    }else{
                        $priceasalvalue     = $price[$i];
                    }

                    $pembeliandetail                    = new PembelianlcldetailModel;
                    $pembeliandetail->id_pembelianlcl   = $pembelian->id;
                    $pembeliandetail->id_gudang         = $gudang[$i];
                    $pembeliandetail->id_barang         = $iditem[$i];
                    if($idrestok[$i]){
                        $pembeliandetail->id_restok     = $idrestok[$i];
                        if($pembelian->category == 'noimport'){
                            $restokid                   = RestokModel::find($idrestok[$i]);
                            $restokid->id_supplier      = $pembelian->id_supplier;
                            $restokid->jml_permintaan   = $qty[$i];
                            $restokid->jml_datang       = $qty[$i];
                            $restokid->save();
                        }
                    }

                    if($idcommercial[$i]){
                        $pembeliandetail->id_penjualanfromchina = $idcommercial[$i];
                        $commercialinvoice              = PenjualanfromchinaModel::find($idcommercial[$i]);
                        if($commercialinvoice->status != 'complete'){
                            $commercialinvoice->status  = 'process';
                        }

                        if($pembelian->category == 'noimport'){
                            $commercialinvoice->id_supplier = $pembelian->id_supplier;
                            $commercialinvoice->id_matauang = $pembelian->id_matauang;
                            $commercialinvoice->currency    = $matauangid->kode;
                        }

                        if($commercialinvoice->save()){
                            if($pembelian->category == 'noimport'){
                                foreach ($commercialinvoice->detail as $key => $value) {
                                    if($value->id_restok == $idrestok[$i]){
                                        $detail                 = PenjualanfromchinadetailModel::find($value->id);
                                        $detail->qty            = $qty[$i];
                                        $detail->qty_terima     = $qty[$i];
                                        $detail->unit_price_without_tax     = $priceasalvalue;
                                        $detail->total_price_without_tax    = $subtotalconvert;
                                        $detail->unit_price_usd     = $priceasalvalue / $this->data['masterrmbtousd']->nominal;
                                        $detail->total_price_usd    = $subtotalconvert / $this->data['masterrmbtousd']->nominal;
                                        $detail->save();
                                    }
                                }
                            }
                        }
                    }

                    $pembeliandetail->price             = $priceasalvalue;
                    $pembeliandetail->price_idr         = $price[$i]; //IDR
                    $pembeliandetail->qty               = $qty[$i];
                    $pembeliandetail->disc              = $disc[$i];
                    $pembeliandetail->subtotal          = $subtotalconvert;
                    $pembeliandetail->subtotal_idr      = $subtotal[$i]; //IDR
                    $pembeliandetail->ppn               = $priceppn[$i];
                    $pembeliandetail->status_ppn        = $ppn[$i];
                    $pembeliandetail->save();
                }

                $data['idpembelianlcl']                 = $pembelian->id;
                $data['auth']                           = true;
                $data['msg']                            = 'LCL berhasil diupdate';

                echo toJson($data);
                return;
            }
            else{
                echo goResult(false, 'LCL gagal diupdate');
                return;
            }
	}
	public function lcl_hapuslcl_delete($invoice){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
	      
		  $pembelianlcl                   = PembelianlclModel::where('invoice',$invoice)->first();
		  
            if(!$pembelianlcl){
                echo goResult(false, 'Maaf, pembelian lcl tidak ada');
                return;
            }

            if($pembelianlcl->status_process != 'requested'){
                echo goResult(false, 'Maaf, LCL sudah proses di penerimaan');
                return;
            }

            foreach ($pembelianlcl->detail as $key => $value) {
                $detailid                   = PembelianlcldetailModel::find($value->id);
                // $detailid->status_deleted   = '1';
                $detailid->delete();
                if($value->id_penjualanfromchina != NULL || $value->id_penjualanfromchina != ''){
                    $commercial                 = PenjualanfromchinaModel::find($value->id_penjualanfromchina);
                    $commercial->status         = 'requested';
                    $commercial->category_comercial_invoice         = 0;
                    $commercial->save();
                }
            }

            // $pembelianlcl->status_deleted       = '1';
            $pembelianlcl->delete();

            echo goResult(true, 'Data anda berhasil dihapus');
            return;
	}
	public function lcl_local_createpembayaran_post(){
		 $rules      = [
                'required'  => [
                    ['tgl_bayar'],['matauang'],['price']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $str = "";
            for($i = 0; $i < 1; $i++){
                $characters = array_merge(range('0','9'));
                $max = count($characters) - 1;
                for ($j = 0; $j < 5; $j++) {
                    $rand = mt_rand(0, $max);
                    $str .= $characters[$rand];
                }

                $kodeOld            = PembelianlclbayarModel::where('kode', $str)->get();
                if(count($kodeOld) > 0){
                    $str    = "";
                    $i      = 0;
                }
            }

            if (!empty($_FILES['image']['name']) && $this->isImageAndDocument('image')==true) {
                $filename           = 'BUKTI-BYR'.$str;
                $upload             = $this->upload('images/pembelianlclbayar','image',$filename);
                if($upload['auth']  == false){
                    echo goResult(false,$upload['msg']);
                    return;
                }

                $data['bukti']      = $upload['msg']['file_name'];
            }

            $matauang               = MatauangModel::find($this->input->post('matauang'));
            $data['kodepembayaran'] = 'BYR'.$str;
            $data['tgl_bayar_create']      = $this->input->post('tgl_bayar');
            $data['tgl_bayar_name'] = tgl_indo($this->input->post('tgl_bayar'));
            $data['matauang']       = $matauang->id;
            $data['matauang_kode']  = $matauang->kode;
            $data['matauang_name']  = $matauang->name;
            $data['matauang_simbol'] = $matauang->simbol;
            $data['price_create']          = $this->input->post('price');
            $data['price_name']     = number_format($this->input->post('price'));
            $data['keterangan_create']     = $this->input->post('keterangan');
            $data['bukti_pembayaran']     = $this->input->post('bukti');
            $data['auth']           = true;
            $data['msg']            = 'success';

            echo toJson($data);
            return;
	}

	public function lcl_local_savepembayaran_post(){
			$id = $this->input->post('id_lcllocal');
			$pembelian  = PembelianlclModel::find($id);
			// dd(json_encode($id));
            if(!$pembelian){
                echo goResult(false, 'Pembelian LCL tidak ada');
                return;
            }

            $pembayaran     = $this->input->post('kodepembayaran_pembayaran');
            if($pembayaran){
                PembelianlclbayarModel::where('id_pembelianlcl', $id)->delete();
                for ($i=0; $i < count($pembayaran); $i++) {
                    $newpembayaran                  = new PembelianlclbayarModel;
                    $newpembayaran->id_pembelianlcl = $pembelian->id;
                    $newpembayaran->id_matauang     = $this->input->post('matauang_pembayaran')[$i];
                    $newpembayaran->kode            = $this->input->post('kodepembayaran_pembayaran')[$i];
                    $newpembayaran->tgl_bayar       = $this->input->post('tgl_bayar_pembayaran')[$i];
                    $newpembayaran->price           = $this->input->post('price_pembayaran')[$i];
                    $newpembayaran->bukti           = $this->input->post('bukti_pembayaran')[$i];
                    $newpembayaran->keterangan      = $this->input->post('keterangan_pembayaran')[$i];
                    $newpembayaran->status          = '1';
                    $newpembayaran->save();
                }

                echo goResult(true, 'Pembayaran berhasil diupdate');
                return;
            }else{
                echo goResult(false, 'Pembayaran belum di isikan');
                return;
            }
	}

	public function fcl2_get(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$nameFilter 			= $this->input->get('name');
			$nameFilterPerusahaan 			= $this->input->get('name_perusahaan');
			$checkdatevalue 		= $this->input->get('checkdatevalue');

			//status check
			$process_check 			= $this->input->get('process_check');
			// $pending_check 			= $this->input->get('pending_check');
			// $completed_check 		= $this->input->get('completed_check');
			
			if(!isset($process_check)){
				$process_val = 'requested';
			}else{
				$process_val = $process_check;
			}

			if(!$checkdatevalue){
				$checkdatefix 	= 'unchecked';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}

			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}

			if(!$tgl_awal){
				$startDate 		= FclContainerModel::orderBy('date', 'asc')->value('date');
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}

			if(!$nameFilter && !$nameFilterPerusahaan){
				$valuename 				= '';
					$valuenamePerusahaan 				= '';
				}
			elseif(!$nameFilter){
					$valuename 				= '';
						$valuenamePerusahaan 				= $nameFilterPerusahaan;
			}
			elseif(!$nameFilterPerusahaan){
					$valuenamePerusahaan = '';
						$valuename 				= $nameFilter;
			}
			else{
				$valuename 				= $nameFilter;
				$valuenamePerusahaan 				= $nameFilterPerusahaan;
			}
			// else{
			// 	$valuename 				= $nameFilter;
			// 	$valuenamePerusahaan 				= $nameFilterPerusahaan;
			// }

			$status_arr = [$process_val];

			if($checkdatefix == 'checked'){

		
				$fclcontainer 			= FclContainerModel::where('status_deleted', '0')->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $lastDate)->where('name', 'LIKE', '%'.$valuename.'%')->whereIn('status_process', $status_arr)->orderBy('date', 'desc')->get();

			}else{
		
				$fclcontainer 			= FclContainerModel::where('status_deleted', '0')->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $lastDate)->where('name', 'LIKE', '%'.$valuename.'%')->whereIn('status_process', $status_arr)->orderBy('date', 'desc')->get();
			}

			$idFclcontainer 			= array();
			foreach ($fclcontainer as $key => $value) {
				$idFclcontainer[] 		= $value->id;
			}
			
			$total						= count($idFclcontainer);
			$data['fclcontainer'] 		= FclContainerModel::with(['supplier'])->whereIn('id', $idFclcontainer)->orderBy('date', 'desc')->get();
			
			$fclcontainer_detail = FclContainerdetailModel::whereIn('id_fclcontainer', $idFclcontainer)->get();
			$idFclcontainer_detail 			= array();
			foreach ($fclcontainer_detail as $key => $value) {
				$idFclcontainer_detail[] 		= $value->id_penjualanfromchina;
			}
			$data['fclcontainer_detail'] = $fclcontainer_detail;
			$penjualanfromchina = PenjualanfromchinaModel::whereIn('id',$idFclcontainer_detail)->get();
			$data['penjualanfromchina'] = $penjualanfromchina;
			
			$data['tgl_awal'] 			= $startDate;
			$data['tgl_akhir'] 			= $lastDate;
			$data['checkdatevalue'] 	= $checkdatefix;
			$data['nameFilter'] 		= $valuename;

			//status check
			$data['request_check'] 		= $process_val;
			$idFclcontainers = [/* array of ids */];
			$matauang = [/* array of ids */];
			$processedContainers = [];
			$fclcontainers = FclContainerModel::whereIn('id', $idFclcontainer)->orderBy('date', 'desc')->get();
			// Proses perhitungan dan penambahan properti pada setiap container
			foreach ($fclcontainers as $container) {
			    $total = 0;

			    foreach ($container->detail as $detail) {
			        foreach ($detail->detailcommercial as $commercial) {
			            foreach ($commercial->detail as $finalprice) {
			                if ($finalprice->total_price_usd == 0) {
			                    $price = $container->status_rate == '1'
			                        ? $finalprice->total_price_without_tax / $container->rate
			                        : $finalprice->total_price_without_tax;
			                } else {
			                    $price = $finalprice->total_price_usd;
			                }
			                $total += $price;
			            }
			        }
			    }


			    // Menambahkan properti yang sudah dihitung ke dalam array
			    $processedContainers[] = [
			        'container' => $container,
			        'total' => $total,
				
			    ];
				$matauang[] =$container->matauang->simbol;
				$idFclcontainers[]=$container->freight_cost + $container->insurance + round($total, 2);
			}
			$data['simbolmatauang'] = $matauang;
			$data['fclcontainerBaru'] = $idFclcontainers;
			$data['matauang'] = MatauangModel::where('status','1')->orderBy('id','asc')->get();
			$listorder 				= RestokModel::where('status_deleted', '0')->where('id_supplier', '!=', 0)->where('status', 'requested')->groupBy('id_supplier')->get();

			$idsupplier 			= array();
			foreach ($listorder as $key => $value) {
				$idsupplier[] 		= $value->id_supplier;
			}

			$this->db->select('restok.*, barang.*, supplier.name as supplier_name,restok.id as restok_id'); 
			$this->db->from('restok'); 
			$this->db->join('barang', 'barang.id = restok.id_barang'); 
			$this->db->join('supplier','supplier.id = restok.id_supplier');
			$this->db->where('restok.status_deleted', '0');
			
			
			$this->db->where('restok.status', 'requested');
			$this->db->group_by('supplier.name');
			$this->db->order_by('restok.id', 'DESC'); 

			$query = $this->db->get();
			$data['listorder'] = $query->result(); 

			$data['supplier'] 		= SupplierModel::whereIn('id', $idsupplier)->where('status', '1')->orderBy('name', 'asc')->get();
			$data['list_supplier'] 		= SupplierModel::where('status', '1')->orderBy('name', 'asc')->get();
			
			$data['bank_supplier'] 		= SupplierbankModel::where('status', '1')->orderBy('bank_name', 'asc')->get();
			


			$penjualan 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('status', 'requested')->orderBy('date', 'desc')->get();
		
			$idPenjualan 				= array();

			foreach ($penjualan as $key => $value) {

				$idPenjualan[] 			= $value->id;

			}
			$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang'])
		
			->whereIn('penjualanfromchina.id', $idPenjualan)
			->where('penjualanfromchina.category_comercial_invoice','fcl')
			->orderBy('penjualanfromchina.invoice_no', 'desc')
			;


			$totalRowsPenjualan = $penjualanQuery->count();

			$comercialInvoice = $penjualanQuery->get();
			$data['importlistlcl'] = $comercialInvoice;
			$data['rmbtousd'] = RmbtousdModel::find(1);
			$data['directory_gambar'] = 'https://maxipro.id/images/barang/';
			echo goResult(true,$data);
			return;
	}

	public function fcl_importbarang_post(){
				$teknisi = $this->validate_teknisi_id();
		        if (!$teknisi) {
		            return; // Stop further execution if validation fails
		        }

				$penjualan 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('status', 'requested')->orderBy('date', 'desc')->get();
				
				$id_commercial = $this->input->post('id_commercial');
				$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang'])
				
				->whereIn('penjualanfromchina.id', $id_commercial)
				->where('penjualanfromchina.category_comercial_invoice','fcl')
				->orderBy('penjualanfromchina.invoice_no', 'desc');



				$totalRowsPenjualan = $penjualanQuery->count();

				$comercialInvoice = $penjualanQuery->get();
				$data['importlistlcl'] = $comercialInvoice;

				$data['directory_gambar'] = 'https://maxipro.id/images/barang/';

				
				$array_id_resok = [];
				foreach ($data['importlistlcl'] as $key => $value) {
					foreach ($value['detail'] as $index => $result) {
						$array_id_resok[] = $result['id_restok'];
					}
				}

				if (!empty($array_id_resok)) {
		    		// Query untuk mendapatkan barang dan restok berdasarkan id_restok
					$this->db->select('barang.id, barang.name, barang.new_kode, barang.image, barang.name_china, barang.name_english, restok.id as restok_id');
							$this->db->from('restok');
				    $this->db->join('barang', 'restok.id_barang = barang.id'); // Kondisi join
				    $this->db->where('barang.status_deleted', '0');
				    
				    // Filter berdasarkan array $array_id_resok
				    $this->db->where_in('restok.id', $array_id_resok);

				    // Urutkan berdasarkan urutan array $array_id_resok menggunakan FIELD()
				    $order_by_ids = implode(',', $array_id_resok);
				    $this->db->order_by("FIELD(restok.id, $order_by_ids)", '', false);

				    $query = $this->db->get();
				    $data['product'] = $query->result_array();
				}

				echo goResult(true,$data);
				return;
	}

	public function fcl_create_post(){
		$teknisi = $this->validate_teknisi_id();

			if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
	        $modeadmin                      = $this->input->post('modeadmin');
	        if($modeadmin == '1'){
	        	$rulesone = [
	        		'required'  => [
	        			['invoicenumber'],['contractnumber'],['packingnumber']
	        		]
	        	];

	        	$validateone    = Validation::check($rulesone,'post');
	        	if(!$validateone->auth){
	        		echo goResult(false,$validateone->msg);
	        		return;
	        	}
	        }

	        $rules = [
	        	'required'  => [
	        		['database'],['tgl_transaksi'],['supplier'],['address'],['city'],['telephone']
	        	]
	        ];

	        $validate   = Validation::check($rules,'post');
	        if(!$validate->auth){
	        	echo goResult(false,$validate->msg);
	        	return;
	        }

	        $commercial         = $this->input->post('idpenjualanfromchina');
	        $detailcommercial   = $this->input->post('idpenjualanfromchinadetail');
	        if(!$commercial){
	        	echo goResult(false, "Commercialinvoice not found");
	        	return;
	        }

	        $supplier           = SupplierModel::find($this->input->post('supplier'));
	        if(!$supplier){
	        	echo goResult(false, "Supplier not found");
	        	return;
	        }

            if($this->input->post('currency') == 2){ //USD
            	if($this->input->post('valuestatusrate') == '0' && $this->input->post('valuerate') <= 0){
            		echo goResult(false, "Convert not found");
            		return;
            	}elseif($this->input->post('valuestatusrate') == '1' && $this->input->post('valuerate') <= 0){
            		echo goResult(false, "Convert not found");
            		return;
            	}elseif($this->input->post('valuestatusrate') == '0' && $this->input->post('valuerate') > 0){
            		echo goResult(false, "Convert not found");
            		return;
            	}else{}
            }

            //generate code
            $codeorder  = FclContainerModel::whereDate('date', '=', date('Y-m-d'))->desc()->get();
            $isToday    = explode('-', date('Y-m-d'));
            $isYear     = $isToday[0];
            $year       = substr($isYear, -2);
            $month      = $isToday[1];
            $day        = $isToday[2];
            if(!$codeorder){
            	$newcodeorder       = $year.''.$month.''.$day.'01';
            }else{
            	$newcode            = count($codeorder) + 1;
            	if($newcode > 0 && $newcode <= 9){
            		$newcodeorder   = $year.''.$month.''.$day.'0'.$newcode;
            	}else{
            		$newcodeorder   = $year.''.$month.''.$day.$newcode;
            	}
            }

            if($modeadmin == '1'){
            	$invoiceno                      = $this->input->post('invoicenumber');
            	$packingno                      = $this->input->post('contractnumber');
            	$contractno                     = $this->input->post('packingnumber');
            }else{
            	$invoiceno                      = $newcodeorder;
            	$packingno                      = $newcodeorder;
            	$contractno                     = $newcodeorder;
            }

            $fclcontainer                       = new FclContainerModel;
            $fclcontainer->invoice_no           = $invoiceno;
            $fclcontainer->packing_no           = $packingno;
            $fclcontainer->contract_no          = $contractno;
            $fclcontainer->mode_admin           = $modeadmin;
            $fclcontainer->name_db              = $this->input->post('database');
            $fclcontainer->id_supplier          = $this->input->post('supplier');
            $fclcontainer->id_supplierbank      = $this->input->post('supplierbank');
            $fclcontainer->name                 = $supplier->name;
            $fclcontainer->address              = $this->input->post('address');
            $fclcontainer->city                 = $this->input->post('city');
            $fclcontainer->phone                = $this->input->post('telephone');
            $fclcontainer->date                 = $this->input->post('tgl_transaksi');
            $fclcontainer->incoterms            = $this->input->post('incoterms');
            $fclcontainer->location             = $this->input->post('location');
            $fclcontainer->id_matauang          = $this->input->post('currency');
            $fclcontainer->freight_cost         = $this->input->post('freight_cost');
            $fclcontainer->insurance            = $this->input->post('insurance');
            $fclcontainer->rate                 = $this->input->post('valuerate');
            $fclcontainer->status_rate          = $this->input->post('valuestatusrate');
            $fclcontainer->bank_name            = $this->input->post('bank_name');
            $fclcontainer->bank_address         = $this->input->post('bank_address');
            $fclcontainer->swift_code           = $this->input->post('swift_code');
            $fclcontainer->account_no           = $this->input->post('account_no');
            $fclcontainer->beneficiary_name     = $this->input->post('beneficiary_name');
            $fclcontainer->beneficiary_address  = $this->input->post('beneficiary_address');
            $fclcontainer->nominal_convert		= 0;
            if($fclcontainer->save()){
            	for ($i=0; $i < count($commercial); $i++) { 
            		$fclcontainerdetail                             = new FclContainerdetailModel;
            		$fclcontainerdetail->id_fclcontainer            = $fclcontainer->id;
            		$fclcontainerdetail->id_penjualanfromchina      = $commercial[$i];
            		$fclcontainerdetail->save();
            		$commercialid                                   = PenjualanfromchinaModel::find($commercial[$i]);
            		$commercialid->status                           = 'process';
            		$commercialid->save();
            	}

            	for ($i=0; $i < count($detailcommercial); $i++) { 
            		$detailcommercialid                             = PenjualanfromchinadetailModel::find($detailcommercial[$i]);
            		$detailcommercialid->unit_price_usd            = $this->input->post('unitusd')[$i];
            		$detailcommercialid->total_price_usd            = $this->input->post('totalusd')[$i];
            		$detailcommercialid->save();
            	}

            	echo goResult(true, "FCL container berhasil dibuat");
            	return;
            }else{
            	echo goResult(false, "FCL container gagal dibuat");
            	return;
            }
	}
	public function fcl_edit_post(){
		 $teknisi = $this->validate_teknisi_id();

	     if (!$teknisi) {
	            return; // Stop further execution if validation fails
	     }
		 $id 						 = $this->input->post('id_fcl');
		 $fclcontainer               = FclContainerModel::find($id);
            if(!$fclcontainer){
                echo goResult(false, 'Maaf, FCL container tidak ada');
                return;
            }

            $modeadmin                      = $this->input->post('modeadmin');
            if($modeadmin == '1'){
                $rulesone = [
                    'required'  => [
                        ['invoicenumber'],['contractnumber'],['packingnumber']
                    ]
                ];

                $validateone    = Validation::check($rulesone,'post');
                if(!$validateone->auth){
                    echo goResult(false,$validateone->msg);
                    return;
                }
            }

            $rules = [
                'required'  => [
                    ['database'],['tgl_transaksi'],['supplier'],['address'],['city'],['telephone']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            //backoldrequested
            $backrequested                      = FclContainerdetailModel::where('id_fclcontainer', $fclcontainer->id)->get();
            foreach ($backrequested as $key => $value) {
                $commercialid                   = PenjualanfromchinaModel::find($value->id_penjualanfromchina);
                $commercialid->status           = 'requested';
                $commercialid->save();
            }

            $commercial         = $this->input->post('idpenjualanfromchina');
            $detailcommercial   = $this->input->post('idpenjualanfromchinadetail');
            if(!$commercial){
                echo goResult(false, "Maaf, commercialinvoice tidak ada");
                return;
            }

            $supplier           = SupplierModel::find($this->input->post('supplier'));
            if(!$supplier){
                echo goResult(false, "Supplier not found");
                return;
            }

            if($this->input->post('currency') == 2){ //USD
                if($this->input->post('valuestatusrate') == '0' && $this->input->post('valuerate') <= 0){
                    echo goResult(false, "Convert not found");
                    return;
                }elseif($this->input->post('valuestatusrate') == '1' && $this->input->post('valuerate') <= 0){
                    echo goResult(false, "Convert not found");
                    return;
                }elseif($this->input->post('valuestatusrate') == '0' && $this->input->post('valuerate') > 0){
                    echo goResult(false, "Convert not found");
                    return;
                }else{}
            }

            //generate code
            if($modeadmin == '1'){
                $invoiceno                      = $this->input->post('invoicenumber');
                $packingno                      = $this->input->post('contractnumber');
                $contractno                     = $this->input->post('packingnumber');
            }else{
                $invoiceno                      = $fclcontainer->invoice_no;
                $packingno                      = $fclcontainer->packing_no;
                $contractno                     = $fclcontainer->contract_no;
            }

            $fclcontainer->invoice_no           = $invoiceno;
            $fclcontainer->packing_no           = $packingno;
            $fclcontainer->contract_no          = $contractno;
            $fclcontainer->mode_admin           = $modeadmin;
            $fclcontainer->name_db              = $this->input->post('database');
            $fclcontainer->id_supplier          = $this->input->post('supplier');
            $fclcontainer->id_supplierbank      = $this->input->post('supplierbank');
            $fclcontainer->name                 = $supplier->name;
            $fclcontainer->address              = $this->input->post('address');
            $fclcontainer->city                 = $this->input->post('city');
            $fclcontainer->phone                = $this->input->post('telephone');
            $fclcontainer->date                 = $this->input->post('tgl_transaksi');
            $fclcontainer->incoterms            = $this->input->post('incoterms');
            $fclcontainer->location             = $this->input->post('location');
            $fclcontainer->id_matauang          = $this->input->post('currency');
            $fclcontainer->freight_cost         = $this->input->post('freight_cost');
            $fclcontainer->insurance            = $this->input->post('insurance');
            $fclcontainer->rate                 = $this->input->post('valuerate');
            $fclcontainer->status_rate          = $this->input->post('valuestatusrate');
            $fclcontainer->bank_name            = $this->input->post('bank_name');
            $fclcontainer->bank_address         = $this->input->post('bank_address');
            $fclcontainer->swift_code           = $this->input->post('swift_code');
            $fclcontainer->account_no           = $this->input->post('account_no');
            $fclcontainer->beneficiary_name     = $this->input->post('beneficiary_name');
            $fclcontainer->beneficiary_address  = $this->input->post('beneficiary_address');
            if($fclcontainer->save()){
                FclContainerdetailModel::where('id_fclcontainer', $fclcontainer->id)->delete();
                for ($i=0; $i < count($commercial); $i++) { 
                    $fclcontainerdetail                             = new FclContainerdetailModel;
                    $fclcontainerdetail->id_fclcontainer            = $fclcontainer->id;
                    $fclcontainerdetail->id_penjualanfromchina      = $commercial[$i];
                    $fclcontainerdetail->save();
                    $commercialid                                   = PenjualanfromchinaModel::find($commercial[$i]);
                    $commercialid->status                           = 'process';
                    $commercialid->save();
                }

                for ($i=0; $i < count($detailcommercial); $i++) { 
                    $detailcommercialid                             = PenjualanfromchinadetailModel::find($detailcommercial[$i]);
                    $detailcommercialid->unit_price_usd            = $this->input->post('unitusd')[$i];
                    $detailcommercialid->total_price_usd            = $this->input->post('totalusd')[$i];
                    $detailcommercialid->save();
                }

                echo goResult(true, "FCL container berhasil diedit");
                return;
            }else{
                echo goResult(false, "FCL container gagal diedit");
                return;
            }
	}

	public function fcl_editview_get($invoice){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$penjualan = FclContainerModel::where('invoice_no', $invoice)->first();



            if(!$penjualan){

				echo json_encode($this->response([



					'success' => false,



					'message' => 'Invoice not found'



				], 404));







				return;

			
            }

            $idpenjualan                = array();
            foreach ($penjualan->detail as $key => $value) {

                $idpenjualan[]          = $value->id_penjualanfromchina;
            }

            $data['type']                   = 'update';
            $data['fclcontainer']           = $penjualan;
            $data['commercialinvoiceedit']  = PenjualanfromchinaModel::whereIn('id', $idpenjualan)->where('status_deleted', '0')->desc()->get();
            $data['supplierbank']           = SupplierbankModel::where('id_supplier', $penjualan->id_supplier)->where('status', '1')->asc()->get();

            $idpenjualan_detail = array();

            foreach ($data['commercialinvoiceedit'] as $key2 => $result) {
            	$idpenjualan_detail[] = $result->id;
            }

            $data['list_comercial_invoice'] = PenjualanfromchinadetailModel::whereIn('id_penjualanfromchina',$idpenjualan_detail)
            	->join('restok','penjualanfromchina_detail.id_restok','=','restok.id')
            	->select('penjualanfromchina_detail.*','restok.id_barang')
            	->get();
            	// dd(json_encode($data['list_comercial_invoice']));
            $id_barang_detail = array();
            foreach ($data['list_comercial_invoice'] as $key3 => $result_barang) {
            	$id_barang_detail[] = $result_barang->id_barang;
            }
            // dd($id_barang_detail);
            $data['barang']				= BarangModel::whereIn('id',$id_barang_detail)->get();
            $data['hscodehistory']		= BarangHscodeModel::whereIn('id_barang',$id_barang_detail)->get();
            $data['list_supplier'] 		= SupplierModel::where('status', '1')->orderBy('name', 'asc')->get();
			$data['matauang']			= MatauangModel::where('status','1')->get();
			$data['rmbtousd'] = RmbtousdModel::find(1);

			$penjualan_import 				= PenjualanfromchinaModel::where('status_deleted', '0')->where('status', 'requested')->orderBy('date', 'desc')->get();
		
			$idPenjualan 				= array();

			foreach ($penjualan_import as $key => $value) {

				$idPenjualan[] 			= $value->id;

			}
			$combinedPenjualan = array_unique(array_merge($idPenjualan,$idpenjualan_detail));
			$penjualanQuery = PenjualanfromchinaModel::with(['supplier', 'detail', 'matauang'])
		
			->whereIn('penjualanfromchina.id', $combinedPenjualan)
			->where('penjualanfromchina.category_comercial_invoice','fcl')
			->orderBy('penjualanfromchina.invoice_no', 'desc')
			;


			$totalRowsPenjualan = $penjualanQuery->count();

			$comercialInvoice = $penjualanQuery->get();
			$data['importlistlcl'] = $comercialInvoice;

            echo goResult(true,$data);
            return;

	}

	public function fcl_detail_get($invoice){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$penjualan = FclContainerModel::where('invoice_no', $invoice)->first();



            if(!$penjualan){

				echo json_encode($this->response([



					'success' => false,



					'message' => 'Invoice not found'



				], 404));







				return;

			
            }

            $idpenjualan                = array();
            foreach ($penjualan->detail as $key => $value) {

                $idpenjualan[]          = $value->id_penjualanfromchina;
            }

            $data['type']                   = 'update';
            $data['fclcontainer']           = $penjualan;
            $data['commercialinvoiceedit']  = PenjualanfromchinaModel::whereIn('id', $idpenjualan)->where('status_deleted', '0')->desc()->get();
            $data['supplierbank']           = SupplierbankModel::where('id_supplier', $penjualan->id_supplier)->where('status', '1')->asc()->get();

            $idpenjualan_detail = array();

            foreach ($data['commercialinvoiceedit'] as $key2 => $result) {
            	$idpenjualan_detail[] = $result->id;
            }
            $data['list_comercial_invoice'] = PenjualanfromchinadetailModel::whereIn('id_penjualanfromchina',$idpenjualan_detail)
            	->join('restok','penjualanfromchina_detail.id_restok','=','restok.id')
            	->select('penjualanfromchina_detail.*','restok.id_barang')
            	->get();
            	
            $id_barang_detail = array();
            foreach ($data['list_comercial_invoice'] as $key3 => $result_barang) {
            	$id_barang_detail[] = $result_barang->id_barang;
            }
            // dd($id_barang_detail);
            $data['barang']				= BarangModel::whereIn('id',$id_barang_detail)->get();
            $data['hscodehistory']		= BarangHscodeModel::whereIn('id_barang',$id_barang_detail)->get();
            $data['list_supplier'] 		= SupplierModel::where('status', '1')->orderBy('name', 'asc')->get();
			$data['matauang']			= MatauangModel::where('status','1')->get();
			$data['rmbtousd'] = RmbtousdModel::find(1);
            echo goResult(true,$data);
            return;

	}

	public function fcl_hapus_delete($invoice){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	
		 	$fclcontainer       = FclContainerModel::where('invoice_no',$invoice)->first();
		 	
            if(!$fclcontainer){
                echo goResult(false, 'Maaf, fcl container tidak ada');
                return;
            }

            if($fclcontainer->status_process != 'requested'){
                echo goResult(false, 'Maaf, FCL sudah proses di penerimaan');
                return;
            }

            $detail             = FclContainerdetailModel::where('id_fclcontainer', $fclcontainer->id)->get();
            foreach ($detail as $key => $value) {
                $detailid                   = FclContainerdetailModel::find($value->id);
                $detailid->status_deleted   = '1';
                $detailid->save();
                $commercial             = PenjualanfromchinaModel::find($value->id_penjualanfromchina);
                $commercial->status     = 'requested';
                $commercial->save();
            }

            $fclcontainer->status_deleted = '1';
            $fclcontainer->save();

            echo goResult(true, 'Data anda berhasil dihapus');
            return;
	}

	public function local_create_post(){
			
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }

			$rules 		= [
				'required' 	=> [
					['database'],['tgl_transaksi'],['termin'],['account'],['supplier'],['matauang'],['cabang']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			//convert
			if($this->input->post('statusconvert') == ''){
				$statusconvert 	= 0;
			}else{
				$statusconvert 	= $this->input->post('statusconvert');
			}

			$nominalconvert 	= $this->input->post('valuenominalconvert');
			$categoryconvert 	= $this->input->post('valuecategoryconvert');
			$iditem 		= $this->input->post('iditem');
			$idrestok 		= $this->input->post('idrestok');
			$idcommercial 	= $this->input->post('idcommercial');
			$item 			= $this->input->post('item');
			// dd($item);
			if($this->input->post('matauang_asal') != 1){ //Jika Asal Sudah IDR
				$priceasal 	= $this->input->post('price_asal');
			}

			$price 			= $this->input->post('price'); //IDR
			
			$qty 			= $this->input->post('qty');
			$disc 			= $this->input->post('disc');
			$subtotal 		= $this->input->post('subtotal');
			$ppn 			= $this->input->post('ppn_item');
			$priceppn 		= $this->input->post('priceppn');
			$gudang 		= $this->input->post('gudang');
			if($this->input->post('database') == 'PT'){
				$lastpembelian 			= PembelianlclModel::where('name_db', 'PT')->desc()->first();
				if(!$lastpembelian){
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodeorder 		= 'LOCALPT-'.$year.''.$month.'-001';
				}else{
					$today 				= explode(' ', $lastpembelian->created_at);
					$dateToday 			= substr($today[0], 0, -3);
					$allpembelian 		= PembelianlclModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
					if($dateToday == date('Y-m')){
						$year 					= substr(date('Y'), -2);
						$newcode 				= count($allpembelian) + 1;
						if($newcode > 0 && $newcode <= 9){
							$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode;
						}elseif($newcode > 9 && $newcode <= 99){
							$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode;
						}else{
							$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode;
						}

						$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
						if(count($lastSelection) > 0){
							$newcode2 			= $newcode + 1;
							if($newcode2 > 0 && $newcode2 <= 9){
								$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode2;
							}elseif($newcode2 > 9 && $newcode2 <= 99){
								$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode2;
							}else{
								$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode2;
							}
						}else{
							$newcodeorder 		= $newSelectioncode;
						}
					}else{
						$isToday 			= explode('-', date('Y-m-d'));
						$isYear 			= $isToday[0];
						$year 				= substr($isYear, -2);
						$month 				= $isToday[1];
						$day 				= $isToday[2];
						$newcodeorder 		= 'LOCALPT-'.$year.''.$month.'-001';
					}
				}
			}else{
				$lastpembelian 	= PembelianlclModel::where('name_db', 'UD')->desc()->first();
				if(!$lastpembelian){
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
				}else{
					$today 				= explode(' ', $lastpembelian->created_at);
					$dateToday 			= substr($today[0], 0, -3);
					$allpembelian 		= PembelianlclModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
					if($dateToday == date('Y-m')){
						$year 					= substr(date('Y'), -2);
						$newcode 				= count($allpembelian) + 1;
						if($newcode > 0 && $newcode <= 9){
							$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode;
						}elseif($newcode > 9 && $newcode <= 99){
							$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode;
						}else{
							$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode;
						}

						$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
						if(count($lastSelection) > 0){
							$newcode2 			= $newcode + 1;
							if($newcode2 > 0 && $newcode2 <= 9){
								$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode2;
							}elseif($newcode2 > 9 && $newcode2 <= 99){
								$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode2;
							}else{
								$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode2;
							}
						}else{
							$newcodeorder 		= $newSelectioncode;
						}
					}else{
						$isToday 			= explode('-', date('Y-m-d'));
						$isYear 			= $isToday[0];
						$year 				= substr($isYear, -2);
						$month 				= $isToday[1];
						$day 				= $isToday[2];
						$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
					}
				}
			}

			$itemnull =	0;
			for ($i=0; $i < count($item); $i++) { 
				if($item[$i] == ''){
					$itemnull = $itemnull + 1;
				}
			}

			$gudangnull = 0;
			for ($i=0; $i < count($gudang); $i++) { 
				if($gudang[$i] == ''){
					$gudangnull = $gudangnull + 1;
				}
			}

			$qtynull =	0;
			for ($i=0; $i < count($qty); $i++) { 
				if($qty[$i] == ''){
					$qtynull = $qtynull + 1;
				}

				if($qty[$i] == 0){
					$qtynull = $qtynull + 1;
				}
			}

			if($itemnull > 0){
				echo goResult(false, 'Item is required');
				return;
			}

			if($qtynull > 0){
				echo goResult(false, 'Qty is required');
				return;
			}

			if($gudangnull > 0){
				echo goResult(false, 'Gudang is required');
				return;
			}

			if($this->input->post('matauang_asal') == 1){
				//Asal IDR
				$pricenull =	0;
				for ($i=0; $i < count($price); $i++) { 
					if($price[$i] == ''){
						$pricenull = $pricenull + 1;
					}

					if($price[$i] == 0){
						$pricenull = $pricenull + 1;
					}
				}

				if($pricenull > 0){
					echo goResult(false, 'Price is required');
					return;
				}
			}else{
				//Asal Not IDR
				$priceasalnull =	0;
				for ($i=0; $i < count($priceasal); $i++) { 
					if($priceasal[$i] == ''){
						$priceasalnull = $priceasalnull + 1;
					}

					if($priceasal[$i] == 0){
						$priceasalnull = $priceasalnull + 1;
					}
				}

				if($priceasalnull > 0){
					echo goResult(false, 'Price asal is required');
					return;
				}

				//IDR
				$pricenull =	0;
				for ($i=0; $i < count($price); $i++) { 
					if($price[$i] == ''){
						$pricenull = $pricenull + 1;
					}

					if($price[$i] == 0){
						$pricenull = $pricenull + 1;
					}
				}

				if($pricenull > 0){
					echo goResult(false, 'Price IDR is required');
					return;
				}

				if($statusconvert == 0){
					echo goResult(false, 'Data not convert to IDR');
					return;
				}
			}
				
			if($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
			}elseif($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '0'){
				$statusppn 	= '1';
				$includeppn = '0';
			}elseif($this->input->post('ppn') == '0' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
			}else{
				$statusppn 	= '0';
				$includeppn = '0';
			}

			if($this->input->post('matauang_asal') != 1){
				$subtotalallasal 		= $this->input->post('td_total') / $nominalconvert;
				$subtotalall 			= $this->input->post('td_total'); //IDR
			}else{
				$subtotalallasal 		= $this->input->post('td_total');
				$subtotalall 			= $this->input->post('td_total'); //IDR
			}

			$pembelian 						= new PembelianlclModel;
			$pembelian->tgl_transaksi 		= $this->input->post('tgl_transaksi');
			$pembelian->name_db 			= $this->input->post('database');
			$pembelian->invoice 			= $newcodeorder;
			$pembelian->id_termin 			= $this->input->post('termin');
			$pembelian->id_account 			= $this->input->post('account');
			$pembelian->id_matauang 		= $this->input->post('matauang_asal');
			$pembelian->id_teknisi 			= $teknisi_id;
			$pembelian->id_cabang 			= $this->input->post('cabang');
			$pembelian->id_supplier 		= $this->input->post('supplier');
			$pembelian->ppn 				= $this->input->post('td_ppn');
			$pembelian->discount 			= $this->input->post('td_discount');
			$pembelian->discount_nominal 	= $this->input->post('td_discount_nominal');
			$pembelian->status_ppn 			= $statusppn;
			$pembelian->status_includeppn 	= $includeppn;
			$pembelian->subtotal 			= $subtotalallasal;
			$pembelian->subtotal_idr 		= $subtotalall;
			$pembelian->status_converttorupiah = $statusconvert;
			$pembelian->nominal_convert 	= $nominalconvert;
			$pembelian->category_convert 	= $categoryconvert;
			$pembelian->status 				= '1';
			$pembelian->keterangan          = $this->input->post('keterangan');
			$pembelian->category_transaksi          = $this->input->post('category_comercial_invoice');

			if($pembelian->save()){
				for ($i=0; $i < count($item); $i++) {
					if($this->input->post('matauang_asal') != 1){
						$subtotalconvert 	= $subtotal[$i] / $nominalconvert;
					}else{
						$subtotalconvert 	= $subtotal[$i];
					}

					$pembeliandetail 					= new PembelianlcldetailModel;
					$pembeliandetail->id_pembelianlcl 	= $pembelian->id;
					$pembeliandetail->id_gudang 		= $gudang[$i];
					$pembeliandetail->id_barang 		= $iditem[$i];
					// dd($idrestok);
					if($idrestok[$i]){
						$pembeliandetail->id_restok 		= $idrestok[$i];
					}

					if($idcommercial[$i]){
						$pembeliandetail->id_penjualanfromchina = $idcommercial[$i];
						$commercialinvoice 				= PenjualanfromchinaModel::find($idcommercial[$i]);
						$commercialinvoice->status 		= 'process';
						$commercialinvoice->category_comercial_invoice = $this->input->post('category_comercial_invoice');
						$commercialinvoice->save();
					}

					

					if($this->input->post('matauang_asal') != 1){
						$pembeliandetail->price 		= $priceasal[$i];
					}else{
						$pembeliandetail->price 		= $price[$i];
					}

					$pembeliandetail->price_idr 		= $price[$i]; //IDR
					$pembeliandetail->qty 				= $qty[$i];
					$pembeliandetail->disc 				= $disc[$i];
					$pembeliandetail->subtotal 			= $subtotalconvert;
					$pembeliandetail->subtotal_idr 		= $subtotal[$i]; //IDR
					$pembeliandetail->ppn 				= $priceppn[$i];
					$pembeliandetail->status_ppn 		= $ppn[$i];
					$pembeliandetail->save();
				}

				$data['idpembelianlcl'] 				= $pembelian->id;
				$data['auth'] 							= true;
				$data['msg'] 							= 'LOCAL berhasil dibuat';

				echo toJson($data);
				return;
			}else{
				echo goResult(false, 'LCL gagal dibuat');
				return;
			}
	}

	public function lcl_create_post(){
			
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }

			$rules 		= [
				'required' 	=> [
					['database'],['tgl_transaksi'],['termin'],['account'],['supplier'],['matauang'],['cabang']
				]
			];

			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}

			//convert
			if($this->input->post('statusconvert') == ''){
				$statusconvert 	= 0;
			}else{
				$statusconvert 	= $this->input->post('statusconvert');
			}

			$nominalconvert 	= $this->input->post('valuenominalconvert');
			$categoryconvert 	= $this->input->post('valuecategoryconvert');
			$iditem 		= $this->input->post('iditem');
			$idrestok 		= $this->input->post('idrestok');
			$idcommercial 	= $this->input->post('idcommercial');
			$item 			= $this->input->post('item');
			// dd($item);
			if($this->input->post('matauang_asal') != 1){ //Jika Asal Sudah IDR
				$priceasal 	= $this->input->post('price_asal');
			}

			$price 			= $this->input->post('price'); //IDR
			
			$qty 			= $this->input->post('qty');
			$disc 			= $this->input->post('disc');
			$subtotal 		= $this->input->post('subtotal');
			$ppn 			= $this->input->post('ppn_item');
			$priceppn 		= $this->input->post('priceppn');
			$gudang 		= $this->input->post('gudang');
			if($this->input->post('category_comercial_invoice') =='lcl'){
					if($this->input->post('database') == 'PT'){
						$lastpembelian 			= PembelianlclModel::where('name_db', 'PT')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LCLPT-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LCLPT-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
							}
						}
					}else{
						$lastpembelian 	= PembelianlclModel::where('name_db', 'UD')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LCLUD-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LCLUD-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LCLUD-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLUD-'.$year.''.$month.'-001';
							}
						}
					}
			}
			elseif($this->input->post('category_comercial_invoice') =='local' ){
					if($this->input->post('database') == 'PT'){
						$lastpembelian 			= PembelianlclModel::where('name_db', 'PT')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LOCALPT-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LOCALPT-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LCLPT-'.$year.''.$month.'-001';
							}
						}
					}else{
						$lastpembelian 	= PembelianlclModel::where('name_db', 'UD')->desc()->first();
						if(!$lastpembelian){
							$isToday 			= explode('-', date('Y-m-d'));
							$isYear 			= $isToday[0];
							$year 				= substr($isYear, -2);
							$month 				= $isToday[1];
							$day 				= $isToday[2];
							$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
						}else{
							$today 				= explode(' ', $lastpembelian->created_at);
							$dateToday 			= substr($today[0], 0, -3);
							$allpembelian 		= PembelianlclModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
							if($dateToday == date('Y-m')){
								$year 					= substr(date('Y'), -2);
								$newcode 				= count($allpembelian) + 1;
								if($newcode > 0 && $newcode <= 9){
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode;
								}elseif($newcode > 9 && $newcode <= 99){
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode;
								}else{
									$newSelectioncode 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode;
								}

								$lastSelection 			= PembelianlclModel::where('invoice', $newSelectioncode)->get();
								if(count($lastSelection) > 0){
									$newcode2 			= $newcode + 1;
									if($newcode2 > 0 && $newcode2 <= 9){
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-00'.$newcode2;
									}elseif($newcode2 > 9 && $newcode2 <= 99){
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-0'.$newcode2;
									}else{
										$newcodeorder 	= 'LOCALUD-'.$year.''.date('m').'-'.$newcode2;
									}
								}else{
									$newcodeorder 		= $newSelectioncode;
								}
							}else{
								$isToday 			= explode('-', date('Y-m-d'));
								$isYear 			= $isToday[0];
								$year 				= substr($isYear, -2);
								$month 				= $isToday[1];
								$day 				= $isToday[2];
								$newcodeorder 		= 'LOCALUD-'.$year.''.$month.'-001';
							}
						}
					}	
			}
			

			$itemnull =	0;
			for ($i=0; $i < count($item); $i++) { 
				if($item[$i] == ''){
					$itemnull = $itemnull + 1;
				}
			}

			$gudangnull = 0;
			for ($i=0; $i < count($gudang); $i++) { 
				if($gudang[$i] == ''){
					$gudangnull = $gudangnull + 1;
				}
			}

			$qtynull =	0;
			for ($i=0; $i < count($qty); $i++) { 
				if($qty[$i] == ''){
					$qtynull = $qtynull + 1;
				}

				if($qty[$i] == 0){
					$qtynull = $qtynull + 1;
				}
			}

			if($itemnull > 0){
				echo goResult(false, 'Item is required');
				return;
			}

			if($qtynull > 0){
				echo goResult(false, 'Qty is required');
				return;
			}

			if($gudangnull > 0){
				echo goResult(false, 'Gudang is required');
				return;
			}

			if($this->input->post('matauang_asal') == 1){
				//Asal IDR
				$pricenull =	0;
				for ($i=0; $i < count($price); $i++) { 
					if($price[$i] == ''){
						$pricenull = $pricenull + 1;
					}

					if($price[$i] == 0){
						$pricenull = $pricenull + 1;
					}
				}

				if($pricenull > 0){
					echo goResult(false, 'Price is required');
					return;
				}
			}else{
				//Asal Not IDR
				$priceasalnull =	0;
				for ($i=0; $i < count($priceasal); $i++) { 
					if($priceasal[$i] == ''){
						$priceasalnull = $priceasalnull + 1;
					}

					if($priceasal[$i] == 0){
						$priceasalnull = $priceasalnull + 1;
					}
				}

				if($priceasalnull > 0){
					echo goResult(false, 'Price asal is required');
					return;
				}

				//IDR
				$pricenull =	0;
				for ($i=0; $i < count($price); $i++) { 
					if($price[$i] == ''){
						$pricenull = $pricenull + 1;
					}

					if($price[$i] == 0){
						$pricenull = $pricenull + 1;
					}
				}

				if($pricenull > 0){
					echo goResult(false, 'Price IDR is required');
					return;
				}

				if($statusconvert == 0){
					echo goResult(false, 'Data not convert to IDR');
					return;
				}
			}
				
			if($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
			}elseif($this->input->post('ppn') == '1' && $this->input->post('includeppn') == '0'){
				$statusppn 	= '1';
				$includeppn = '0';
			}elseif($this->input->post('ppn') == '0' && $this->input->post('includeppn') == '1'){
				$statusppn 	= '1';
				$includeppn = '1';
			}else{
				$statusppn 	= '0';
				$includeppn = '0';
			}

			if($this->input->post('matauang_asal') != 1){
				$subtotalallasal 		= $this->input->post('td_total') / $nominalconvert;
				$subtotalall 			= $this->input->post('td_total'); //IDR
			}else{
				$subtotalallasal 		= $this->input->post('td_total');
				$subtotalall 			= $this->input->post('td_total'); //IDR
			}

			$pembelian 						= new PembelianlclModel;
			$pembelian->tgl_transaksi 		= $this->input->post('tgl_transaksi');
			$pembelian->name_db 			= $this->input->post('database');
			$pembelian->invoice 			= $newcodeorder;
			$pembelian->id_termin 			= $this->input->post('termin');
			$pembelian->id_account 			= $this->input->post('account');
			$pembelian->id_matauang 		= $this->input->post('matauang_asal');
			$pembelian->id_teknisi 			= $teknisi->id;
			$pembelian->id_cabang 			= $this->input->post('cabang');
			$pembelian->id_supplier 		= $this->input->post('supplier');
			$pembelian->ppn 				= $this->input->post('td_ppn');
			$pembelian->discount 			= $this->input->post('td_discount');
			$pembelian->discount_nominal 	= $this->input->post('td_discount_nominal');
			$pembelian->status_ppn 			= $statusppn;
			$pembelian->status_includeppn 	= $includeppn;
			$pembelian->subtotal 			= $subtotalallasal;
			$pembelian->subtotal_idr 		= $subtotalall;
			$pembelian->status_converttorupiah = $statusconvert;
			$pembelian->nominal_convert 	= $nominalconvert;
			$pembelian->category_convert 	= $categoryconvert;
			$pembelian->status 				= '1';
			$pembelian->keterangan          = $this->input->post('keterangan');
			$pembelian->category_transaksi          = $this->input->post('category_comercial_invoice');
			if($pembelian->save()){
				for ($i=0; $i < count($item); $i++) {
					if($this->input->post('matauang_asal') != 1){
						$subtotalconvert 	= $subtotal[$i] / $nominalconvert;
					}else{
						$subtotalconvert 	= $subtotal[$i];
					}

					$pembeliandetail 					= new PembelianlcldetailModel;
					$pembeliandetail->id_pembelianlcl 	= $pembelian->id;
					$pembeliandetail->id_gudang 		= $gudang[$i];
					$pembeliandetail->id_barang 		= $iditem[$i];
					// dd($idrestok);
					if($idrestok[$i]){
						$pembeliandetail->id_restok 		= $idrestok[$i];
					}

					if($idcommercial[$i]){
						$pembeliandetail->id_penjualanfromchina = $idcommercial[$i];
						$commercialinvoice 				= PenjualanfromchinaModel::find($idcommercial[$i]);
						$commercialinvoice->status 		= 'process';
						$commercialinvoice->category_comercial_invoice = $this->input->post('category_comercial_invoice');
						$commercialinvoice->save();
					}

					

					if($this->input->post('matauang_asal') != 1){
						$pembeliandetail->price 		= $priceasal[$i];
					}else{
						$pembeliandetail->price 		= $price[$i];
					}

					$pembeliandetail->price_idr 		= $price[$i]; //IDR
					$pembeliandetail->qty 				= $qty[$i];
					$pembeliandetail->disc 				= $disc[$i];
					$pembeliandetail->subtotal 			= $subtotalconvert;
					$pembeliandetail->subtotal_idr 		= $subtotal[$i]; //IDR
					$pembeliandetail->ppn 				= $priceppn[$i];
					$pembeliandetail->status_ppn 		= $ppn[$i];
					$pembeliandetail->save();
				}

				$data['idpembelianlcl'] 				= $pembelian->id;
				$data['auth'] 							= true;
				$data['msg'] 							= 'LCL berhasil dibuat';

				echo toJson($data);
				return;
			}else{
				echo goResult(false, 'LCL gagal dibuat');
				return;
			}
	}

	public function penerimaan_pembelian_get(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }


			$kodeFilter 			= $this->input->get('kode');
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$checkdatevalue 		= $this->input->get('checkdatevalue');
            $lclflcFilter          = $this->input->get('lcl_fcl');
            
			// dd($lclflcFilter);
			if(!$checkdatevalue){
				$checkdatefix 	= '';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}

			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}

			if(!$tgl_awal){
		    $startDate      = date('Y-m-d', strtotime($lastDate. '-7 days'));
				// dd($startDate);
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}

			if(!$kodeFilter && !$lclflcFilter){
				$valuekode 		= '';
				$valuelclflc  	='';
			}else{
				$valuekode 		= $kodeFilter;
                $valuelclflc 	= $lclflcFilter;
                // var_dump($valuelclflc);
			}
			$threeLetters = substr($lclflcFilter, 0, 4);
			
			$invoice_no_fcl = substr($lclflcFilter, 4);

			if($checkdatefix == 'checked'){
				
				if($threeLetters =='INV-'){
					$this->db->select('penerimaan_pembelian.id as id, penerimaan_pembelian.*');
					$this->db->from('penerimaan_pembelian');
					$this->db->join('pembelian_lcl', 'pembelian_lcl.id = penerimaan_pembelian.id_fcl_lcl');
					$this->db->join('fcl_container', 'fcl_container.id = penerimaan_pembelian.id_fcl_lcl');
					$this->db->where('penerimaan_pembelian.kode LIKE', '%'.$valuekode.'%');
					$this->db->where('penerimaan_pembelian.status', '1');
					  $this->db->where('penerimaan_pembelian.category', 'fcl');
					$this->db->where('DATE(penerimaan_pembelian.tgl_terima) >=', $startDate);
					$this->db->where('DATE(penerimaan_pembelian.tgl_terima) <=', $lastDate);
					$this->db->group_start(); // Mulai grup where
					$this->db->or_like('fcl_container.invoice_no', $invoice_no_fcl); // Gunakan or_like untuk mencocokkan kondisi OR
					$this->db->group_end(); // Akhiri grup where
					$this->db->order_by('penerimaan_pembelian.tgl_terima', 'desc');
					$penerimaan = $this->db->get()->result();
				}
				else{
					$this->db->select('penerimaan_pembelian.id as id, penerimaan_pembelian.*');
					$this->db->from('penerimaan_pembelian');
					$this->db->join('pembelian_lcl', 'pembelian_lcl.id = penerimaan_pembelian.id_fcl_lcl');
					$this->db->where('penerimaan_pembelian.kode LIKE', '%'.$valuekode.'%');
					$this->db->where('penerimaan_pembelian.status', '1');
					$this->db->where('DATE(penerimaan_pembelian.tgl_terima) >=', $startDate);
					$this->db->where('DATE(penerimaan_pembelian.tgl_terima) <=', $lastDate);
					$this->db->group_start(); // Mulai grup where
					$this->db->or_like('pembelian_lcl.invoice', $valuelclflc); // Gunakan or_like untuk mencocokkan kondisi OR
					$this->db->group_end(); // Akhiri grup where
					$this->db->order_by('penerimaan_pembelian.tgl_terima', 'desc');
					$penerimaan = $this->db->get()->result();
				}

			}else{

				if($threeLetters =='INV-'){
	 				$this->db->select('penerimaan_pembelian.id as id, penerimaan_pembelian.*');
	                $this->db->from('penerimaan_pembelian');
	                
	                $this->db->join('fcl_container', 'fcl_container.id = penerimaan_pembelian.id_fcl_lcl');
	                $this->db->where('penerimaan_pembelian.kode LIKE', '%'.$valuekode.'%');
	                $this->db->where('penerimaan_pembelian.status', '1');
	                $this->db->where('penerimaan_pembelian.category', 'fcl');
	                        $this->db->where('DATE(penerimaan_pembelian.tgl_terima) >=', $startDate);
                    $this->db->where('DATE(penerimaan_pembelian.tgl_terima) <=', $lastDate);
	                $this->db->group_start(); // Mulai grup where
	                $this->db->or_like('fcl_container.invoice_no', $invoice_no_fcl); // Gunakan or_like untuk mencocokkan kondisi OR
	                $this->db->group_end(); // Akhiri grup where
	                $this->db->order_by('penerimaan_pembelian.tgl_terima', 'desc');
	                $penerimaan = $this->db->get()->result();

				}
				else{
					
					 $this->db->select('penerimaan_pembelian.id as id, penerimaan_pembelian.*,');
	                $this->db->from('penerimaan_pembelian');
	                $this->db->join('pembelian_lcl', 'pembelian_lcl.id = penerimaan_pembelian.id_fcl_lcl');
	                $this->db->where('penerimaan_pembelian.kode LIKE', '%'.$valuekode.'%');
	                $this->db->where('penerimaan_pembelian.status', '1');
	                          $this->db->where('DATE(penerimaan_pembelian.tgl_terima) >=', $startDate);
                    $this->db->where('DATE(penerimaan_pembelian.tgl_terima) <=', $lastDate);
	                $this->db->group_start(); // Mulai grup where
	                $this->db->or_like('pembelian_lcl.invoice', $valuelclflc); // Gunakan or_like untuk mencocokkan kondisi OR
	                $this->db->group_end(); // Akhiri grup where
	                $this->db->order_by('penerimaan_pembelian.tgl_terima', 'desc');
	                $penerimaan = $this->db->get()->result();
				}
              
			}

			$idpenerimaan 			= array();
			$idcategory 			= array();
			foreach ($penerimaan as $key => $value) {
				$idpenerimaan[] 	= $value->id;
				$idcategory[] = $value->category;

			}

			
			$data['penerimaan'] 	= PenerimaanpembelianModel::with(['lcl','fcl','gudang','teknisi'])->whereIn('id', $idpenerimaan)->where('status', '1')->orderBy('tgl_terima', 'desc')->get();


			
			$data['pembelianlcl']   = PembelianlclModel::where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
			$id_arr_pembelianlcl = array();
			foreach ($data['pembelianlcl'] as $key => $value) {
					$id_arr_pembelianlcl[] 	= $value->id;
					

			}
			$countlcl = $data['pembelianlcl']->count();
			$data['countlcl'] = $countlcl;
			$data['pembelianlcldetail'] = PembelianlcldetailModel::whereIn('id_pembelianlcl',$id_arr_pembelianlcl)->desc()->get();

			

			$count = $data['pembelianlcldetail']->count();
			$data['countlcldetail'] = $count;
            $data['fclcontainer']   = FclContainerModel::where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
            $id_fcl_array = array();
            foreach($data['fclcontainer'] as $index => $result){
            	$id_fcl_array[] = $result->id;
            }
            $data['fclcontainerdetail'] = FclContainerdetailModel::whereIn('id_fclcontainer',$id_fcl_array)->get();

            $id_penjualanfromchina_array = array();
            foreach ($data['fclcontainerdetail'] as $index2 => $result) {
            	$id_penjualanfromchina_array[] = $result->id_penjualanfromchina;
            }
            $data['penjualanfromchina_detail'] = PenjualanfromchinadetailModel::whereIn('id_penjualanfromchina',$id_penjualanfromchina_array)->get();
            
            $tot_penjualanfromchina_detail = [];
			$sum_tot = [];
			$sum_qty_terima = [];
			$tot_remaining_qty = []; // New array for the result of $tot_penjualanfromchina_detail - $sum_qty_terima

			foreach ($data['penjualanfromchina_detail'] as $index => $result) {
			    $id = $result['id_penjualanfromchina'];

			    // Check if the current id is already in the arrays
			    if (array_key_exists($id, $tot_penjualanfromchina_detail)) {
			        // If it's a duplicate, sum up the qty and qty_terima
			        $tot_penjualanfromchina_detail[$id] += $result['qty'];
			        $sum_qty_terima[$id] += $result['qty_terima'];
			    } else {
			        // If it's not a duplicate, initialize the qty and qty_terima
			        $tot_penjualanfromchina_detail[$id] = $result['qty'];
			        $sum_qty_terima[$id] = $result['qty_terima'];
			    }
			}

			// Now create the $tot_remaining_qty array by subtracting $sum_qty_terima from $tot_penjualanfromchina_detail
			foreach ($tot_penjualanfromchina_detail as $id => $qty) {
			    $tot_remaining_qty[$id] = $qty - (isset($sum_qty_terima[$id]) ? $sum_qty_terima[$id] : 0);
			}

			// Store the results back in the $data array
			$data['sum_tot'] = $tot_penjualanfromchina_detail;
			$data['sum_qty_terima'] = $sum_qty_terima;
			$data['tot_remaining_qty'] = $tot_remaining_qty;

          

			$data['gudang'] 			= GudangModel::where('status', '1')->asc()->get();
			$data['ekspedisi'] 			= EkspedisiModel::where('status', '1')->orderBy('name', 'asc')->get();

			$data['tgl_awal'] 		= $startDate;
			$data['tgl_akhir'] 		= $lastDate;
			$data['checkdatevalue'] = $checkdatefix;
			$data['kodeFilter'] 	= $valuekode;
            $data['lclflcFilter']     = $valuelclflc;
            echo goResult(true, $data);
            return;

	}
	public function penerimaan_pembelian_addekspedisi_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$rules      = [
                'required'  => [
                    ['tgl_kirim'],['matauang'],['price'],['rute'],['ekspedisi'],['resi']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $id_lcl     = $this->input->post('id_lcl');
            $tgl_kirim  = $this->input->post('tgl_kirim');
            $matauang   = $this->input->post('matauang');
            $price      = $this->input->post('price');
            $rute       = $this->input->post('rute');
            $ekspedisi  = $this->input->post('ekspedisi');
            $resi       = $this->input->post('resi');
            $keterangan = $this->input->post('keterangan');
            $pembelianlcl   = PembelianlclModel::find($id_lcl);
            if(!$pembelianlcl){
                echo goResult(false, 'Lcl not found');
                return;
            }

            $str = "";
            for($i = 0; $i < 1; $i++){
                $characters = array_merge(range('0','9'));
                $max = count($characters) - 1;
                for ($j = 0; $j < 5; $j++) {
                    $rand = mt_rand(0, $max);
                    $str .= $characters[$rand];
                }

                $kodeOld            = PembelianlclekspedisiModel::where('kode', $str)->get();
                if(count($kodeOld) > 0){
                    $str    = "";
                    $i      = 0;
                }
            }

            $newekspedisi                   = new PembelianlclekspedisiModel;
            $newekspedisi->kode             = 'EKS'.$str;
            $newekspedisi->id_pembelianlcl  = $pembelianlcl->id;
            $newekspedisi->id_ekspedisi     = $ekspedisi;
            $newekspedisi->id_matauang      = $matauang;
            $newekspedisi->tgl_kirim        = $tgl_kirim;
            $newekspedisi->rute             = $rute;
            $newekspedisi->price            = $price;
            $newekspedisi->resi             = $resi;
            $newekspedisi->keterangan       = $keterangan;
            $newekspedisi->status           = '1';
            	if ($newekspedisi->save()) {
    				// Memasukkan data newekspedisi ke dalam respons
            		$result = [
            		'success' => true,
            		'message' => 'Ekspedisi success for save',
            		'data' => [
				            'id' => $newekspedisi->id, // ID dari newekspedisi setelah disimpan
				            'kode' => $newekspedisi->kode,
				            'id_pembelianlcl' => $newekspedisi->id_pembelianlcl,
				            'id_ekspedisi' => $newekspedisi->id_ekspedisi,
				            'id_matauang' => $newekspedisi->id_matauang,
				            'tgl_kirim' => $newekspedisi->tgl_kirim,
				            'rute' => $newekspedisi->rute,
				            'price' => $newekspedisi->price,
				            'resi' => $newekspedisi->resi,
				            'keterangan' => $newekspedisi->keterangan
				        ]
				    ];
				    
				    echo goResult(true, $result);
				    return;
				}else{
                echo goResult(false, 'Ekspedisi not success for save');
                return;
            }
	}
	public function penerimaan_pembelian_hapusekspedisi_delete($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$ekspedisilcl       = PembelianlclekspedisiModel::where('kode', $id)->first();
            if(!$ekspedisilcl){
                echo goResult(false, 'Ekspedisi not found');
                return;
            }

            PembelianlclekspedisiModel::find($ekspedisilcl->id)->delete();
            echo goResult(true, 'Deleted success');
            return;
	}

	public function penereimaan_pembelian_addbarang_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		 	$returnvalue            = explode('-', $this->input->post('id'));
			
            $category               = $returnvalue[0];
            $id                     = $returnvalue[1];

            if($category == 'lcl'){
                $pembelian          = PembelianlclModel::find($id);
                $alertmsg           = 'Maaf, LCL tidak ada';
                $namecategory       = 'LCL';
            }else{
                $pembelian          = FclContainerModel::find($id);
                $alertmsg           = 'Maaf, FCL tidak ada';
                $namecategory       = 'FCL';
            }

            if(!$pembelian){
                echo goResult(false, $alertmsg);
                return;
            }

            if($category == 'lcl'){
                $detail             = PembelianlcldetailModel::where('id_pembelianlcl', $id)->where('status_terima', '0')->desc()->get();
                         // dd(json_encode($detail));
                $arrdetail          = array();
                foreach ($detail as $key => $value) {
                    $arrdetail[]    = array(
                        'id'        => $id,
                        'iddetail'  => $value->id,
                        'image'     => $value->barang->imagedir,
                        'kode'      => $value->barang->new_kode,
                        'name'      => $value->barang->name,
                        'idbarang'  => $value->barang->id,
                        'qty_input' => $value->qty,
                        'qty_terima'=> $value->qty_terima,
                        'category'  => $category,
                        'namecategory'  => $namecategory
                    );
                }
            }else{
                $fclcontainer       = FclContainerdetailModel::where('id_fclcontainer', $id)->desc()->get();
                $commercial         = array();
                foreach ($fclcontainer as $key => $value) {
                    $commercial[]   = $value->id_penjualanfromchina;
                }

                $detail             = PenjualanfromchinadetailModel::whereIn('id_penjualanfromchina', $commercial)->where('status_terima', '0')->desc()->get();
                // dd(json_encode($detail));
                $arrdetail          = array();
                foreach ($detail as $key => $value) {
                    $arrdetail[]    = array(
                        'id'        => $id,
                        'iddetail'  => $value->id,
                        'image'     => $value->restok->product->imagedir,
                        'kode'      => $value->restok->product->new_kode,
                        'name'      => $value->restok->product->name,
                        'idbarang'  => $value->restok->id_barang,
                        'qty_input' => $value->qty,
                        'qty_terima'=> $value->qty_terima,
                        'category'  => $category,
                        'namecategory'  => $namecategory
                    );
                }
            }

            $data['auth']           = true;
            $data['msg']            = 'success';
            $data['detail']         = $arrdetail;

            echo toJson($data);
            return;
	}


	public function penerimaan_pembelian_tambah_get(){
		$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	    }
		$data['barang'] = BarangModel::where('status_deleted',0)->desc()->get();
		echo goResult(true,$data);
		return;
	}

	public function penerimaan_pembelian_tambahpenerimaan_post(){
		  $teknisi = $this->validate_teknisi_id();
	      if (!$teknisi) {
	            return; // Stop further execution if validation fails
	      }

		  $rules = [
                'required'  => [
                    ['database'],['tgl_terima'],['pembelianid'],['ekspedisi'],['gudang']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $idlclflc                   = $this->input->post('id');
            $iddetail                   = $this->input->post('id_detail');
            $category                   = $this->input->post('category');
            $item                       = $this->input->post('name');
            $idbarang                   = $this->input->post('id_barang');
            $stokinput                  = $this->input->post('stok_input');
            $stokupdate                 = $this->input->post('stok_update');
            $stokterima                 = $this->input->post('stok_terima');
            $groupfcllcl                = $this->input->post('groupfcllcl');
            if(!$item){
                echo goResult(false, 'Maaf, barang belum dipilih');
                return;
            }

            $qtynull                = 0;
            for ($i=0; $i < count($item); $i++) { 
                if($stokterima[$i] <= 0){
                    $qtynull        = $qtynull + 1;
                }
            }

            if($qtynull > 0){
                echo goResult(false, 'Qty cannot be zero');
                return;
            }

            if($this->input->post('database') == 'PT'){
                $codepindahgudang       = PenerimaanpembelianModel::where('name_db', 'PT')->desc()->first();
                if(!$codepindahgudang){
                    $isToday            = explode('-', date('Y-m-d'));
                    $isYear             = $isToday[0];
                    $year               = substr($isYear, -2);
                    $month              = $isToday[1];
                    $day                = $isToday[2];
                    $newcodepenerimaan  = 'STPPT-'.$year.''.$month.'-001';
                }else{
                    $today              = explode(' ', $codepindahgudang->created_at);
                    $dateToday          = substr($today[0], 0, -3);
                    $allpenjualan       = PenerimaanpembelianModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
                    if($dateToday == date('Y-m')){
                        $year                   = substr(date('Y'), -2);
                        $newcode                = count($allpenjualan) + 1;
                        if($newcode > 0 && $newcode <= 9){
                            $newSelectioncode   = 'STPPT-'.$year.''.date('m').'-00'.$newcode;
                        }elseif($newcode > 9 && $newcode <= 99){
                            $newSelectioncode   = 'STPPT-'.$year.''.date('m').'-0'.$newcode;
                        }else{
                            $newSelectioncode   = 'STPPT-'.$year.''.date('m').'-'.$newcode;
                        }

                        $lastSelection          = PenerimaanpembelianModel::where('kode', $newSelectioncode)->get();
                        if(count($lastSelection) > 0){
                            $newcode2           = $newcode + 1;
                            if($newcode2 > 0 && $newcode2 <= 9){
                                $newcodepenerimaan  = 'STPPT-'.$year.''.date('m').'-00'.$newcode2;
                            }elseif($newcode2 > 9 && $newcode2 <= 99){
                                $newcodepenerimaan  = 'STPPT-'.$year.''.date('m').'-0'.$newcode2;
                            }else{
                                $newcodepenerimaan  = 'STPPT-'.$year.''.date('m').'-'.$newcode2;
                            }
                        }else{
                            $newcodepenerimaan      = $newSelectioncode;
                        }
                    }else{
                        $isToday            = explode('-', date('Y-m-d'));
                        $isYear             = $isToday[0];
                        $year               = substr($isYear, -2);
                        $month              = $isToday[1];
                        $day                = $isToday[2];
                        $newcodepenerimaan  = 'STPPT-'.$year.''.$month.'-001';
                    }
                }
            }else{
                $codepindahgudang       = PenerimaanpembelianModel::where('name_db', 'UD')->desc()->first();
                if(!$codepindahgudang){
                    $isToday            = explode('-', date('Y-m-d'));
                    $isYear             = $isToday[0];
                    $year               = substr($isYear, -2);
                    $month              = $isToday[1];
                    $day                = $isToday[2];
                    $newcodepenerimaan  = 'STPUD-'.$year.''.$month.'-001';
                }else{
                    $today              = explode(' ', $codepindahgudang->created_at);
                    $dateToday          = substr($today[0], 0, -3);
                    $allpenjualan       = PenerimaanpembelianModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
                    if($dateToday == date('Y-m')){
                        $year                   = substr(date('Y'), -2);
                        $newcode                = count($allpenjualan) + 1;
                        if($newcode > 0 && $newcode <= 9){
                            $newSelectioncode   = 'STPUD-'.$year.''.date('m').'-00'.$newcode;
                        }elseif($newcode > 9 && $newcode <= 99){
                            $newSelectioncode   = 'STPUD-'.$year.''.date('m').'-0'.$newcode;
                        }else{
                            $newSelectioncode   = 'STPUD-'.$year.''.date('m').'-'.$newcode;
                        }

                        $lastSelection          = PenerimaanpembelianModel::where('kode', $newSelectioncode)->get();
                        if(count($lastSelection) > 0){
                            $newcode2           = $newcode + 1;
                            if($newcode2 > 0 && $newcode2 <= 9){
                                $newcodepenerimaan  = 'STPUD-'.$year.''.date('m').'-00'.$newcode2;
                            }elseif($newcode2 > 9 && $newcode2 <= 99){
                                $newcodepenerimaan  = 'STPUD-'.$year.''.date('m').'-0'.$newcode2;
                            }else{
                                $newcodepenerimaan  = 'STPUD-'.$year.''.date('m').'-'.$newcode2;
                            }
                        }else{
                            $newcodepenerimaan      = $newSelectioncode;
                        }
                    }else{
                        $isToday            = explode('-', date('Y-m-d'));
                        $isYear             = $isToday[0];
                        $year               = substr($isYear, -2);
                        $month              = $isToday[1];
                        $day                = $isToday[2];
                        $newcodepenerimaan  = 'STPUD-'.$year.''.$month.'-001';
                    }
                }
            }

            $stokerror                  = 0;
            for ($i=0; $i < count($item); $i++) { 
                $stoksisa               = $stokinput[$i] - $stokupdate[$i];
                if($stokterima[$i] > $stoksisa){
                    $stokerror          = $stokerror + 1;
                }
            }

           

            $valuebarang                    = explode('-', $this->input->post('pembelianid'));
            $newpenerimaan                  = new PenerimaanpembelianModel;
            $newpenerimaan->kode            = $newcodepenerimaan;
            $newpenerimaan->name_db         = $this->input->post('database');
            $newpenerimaan->tgl_terima      = $this->input->post('tgl_terima');
            $newpenerimaan->id_ekspedisi    = $this->input->post('ekspedisi');
            $newpenerimaan->id_gudang       = $this->input->post('gudang');
            $newpenerimaan->keterangan      = $this->input->post('keterangan');
            $newpenerimaan->id_teknisi      = $teknisi->id;
            $newpenerimaan->category        = $valuebarang[0];
            $newpenerimaan->id_fcl_lcl      = $valuebarang[1];
            $newpenerimaan->status          = '1';
            if($newpenerimaan->save()){
                $arridlcl               = array();
                $arridcommercial        = array();
                for ($i=0; $i < count($item); $i++) { 
                    $detail                             = new PenerimaanpembeliandetailModel;
                    $detail->id_penerimaanpembelian     = $newpenerimaan->id;
                    $detail->category                   = $category[$i];
                    $detail->id_fcl_lcl                 = $idlclflc[$i];
                    $detail->id_detail                  = $iddetail[$i];
                    $detail->id_barang                  = $idbarang[$i];
                    $detail->name                       = $item[$i];
                    $detail->qty                        = $stokterima[$i];
                    if($detail->save()){
                        if($detail->category == 'lcl'){
                            $arridlcl[]                     = $idlclflc[$i];
                            $lcldetail                      = PembelianlcldetailModel::find($iddetail[$i]);
                            if($lcldetail){
                                $lcldetail->qty_terima          = $lcldetail->qty_terima + $detail->qty;
                                if($lcldetail->qty_terima == $lcldetail->qty){
                                    $lcldetail->status_terima   = '1';
                                }else{
                                    $lcldetail->status_terima   = '0';
                                }

                                if($lcldetail->save()){
                                    $commercialdetail           = PenjualanfromchinaModel::find($lcldetail->id_penjualanfromchina);
                                    if($commercialdetail){
                                        foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                            if($valuecommercialdetail->id_restok == $lcldetail->id_restok){
                                                $commercialdetailid = PenjualanfromchinadetailModel::find($valuecommercialdetail->id);
                                                $commercialdetailid->status_terima = $lcldetail->status_terima;
                                                $commercialdetailid->save();
                                            }
                                        }

                                        $countterima                = 0;
                                        foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                            if($commercialdetailid->status_terima == '1'){
                                                $countterima        = $countterima + 1;
                                            }
                                        }

                                        if($countterima == count($commercialdetail->detail)){
                                            $commercialdetail->status_terima    = '1';
                                            $commercialdetail->status           = 'complete';
                                            $commercialdetail->save();
                                        }else{
                                            $commercialdetail->status_terima    = '0';
                                            $commercialdetail->status           = 'process';
                                            $commercialdetail->save();
                                        }
                                        
                                        $restok                     = RestokModel::find($lcldetail->id_restok);
                                        if($restok){
                                            $restok->jml_datang     = $restok->jml_datang + $detail->qty;
                                            if($restok->jml_datang == $restok->jml_permintaan){
                                                $restok->status         = 'complete';
                                                $restok->status_terima  = '1';
                                            }else{
                                                $restok->status         = 'process';
                                                $restok->status_terima  = '0';
                                            }

                                            $restok->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            $commercialdetail               = PenjualanfromchinadetailModel::find($iddetail[$i]);
                            if($commercialdetail){
                                $commercialdetail->qty_terima   = $commercialdetail->qty_terima + $detail->qty;
                                $arridcommercial[]              = $commercialdetail->id_penjualanfromchina;
                                if($commercialdetail->qty_terima == $commercialdetail->qty){
                                    $commercialdetail->status_terima    = '1';
                                }else{
                                    $commercialdetail->status_terima    = '0';
                                }

                                if($commercialdetail->save()){
                                    $restok                     = RestokModel::find($commercialdetail->id_restok);
                                    if($restok){
                                        $restok->jml_datang     = $restok->jml_datang + $detail->qty;
                                        if($restok->jml_datang == $restok->jml_permintaan){
                                            $restok->status         = 'complete';
                                            $restok->status_terima  = '1';
                                        }else{
                                            $restok->status         = 'process';
                                            $restok->status_terima  = '0';
                                        }

                                        $restok->save();
                                    }
                                }
                            }
                        }
                    }
                }

                if(count($arridlcl) > 0){
                    $lcl                    = PembelianlclModel::whereIn('id', $arridlcl)->asc()->get();
                    foreach ($lcl as $key => $result) {
                        $lclid              = PembelianlclModel::find($result->id);
                        if($lclid){
                            $sumstatusterima    = 0;
                            foreach ($lclid->detail as $key => $value) {
                                if($value->status_terima == '1'){
                                    $sumstatusterima = $sumstatusterima + 1;
                                }
                            }

                            if($sumstatusterima == count($lclid->detail)){
                                $lclid->status_process  = 'complete';
                                $lclid->status_terima   = '1';
                            }else{
                                $lclid->status_terima   = '0';
                                $lclid->status_process  = 'process';
                            }

                            $lclid->save();
                        }
                    }
                }

                if(count($arridcommercial) > 0){
                    $commercial             = PenjualanfromchinaModel::whereIn('id', $arridcommercial)->asc()->get();
                    foreach ($commercial as $key => $result) {
                        $commercialid       = PenjualanfromchinaModel::find($result->id);
                        if($commercialid){
                            $sumstatusterima    = 0;
                            foreach ($commercialid->detail as $key => $value) {
                                if($value->status_terima == '1'){
                                    $sumstatusterima = $sumstatusterima + 1;
                                }
                            }

                            if($sumstatusterima == count($commercialid->detail)){
                                $commercialid->status           = 'complete';
                                $commercialid->status_terima    = '1';
                            }else{
                                $commercialid->status           = 'process';
                                $commercialid->status_terima    = '0';
                            }

                            $commercialid->save();
                        }
                    }

                    $fcldetail              = FclContainerdetailModel::whereIn('id_penjualanfromchina', $arridcommercial)->groupBy('id_fclcontainer')->asc()->get();
                    $arridfcl               = array();
                    foreach ($fcldetail as $key => $value) {
                        $arridfcl[]         = $value->id_fclcontainer;
                    }

                    $fcl                    = FclContainerModel::whereIn('id', $arridfcl)->asc()->get();
                    foreach ($fcl as $key => $value) {
                        $fclid              = FclContainerModel::find($value->id);
                        if($fclid){
                            $sumterimafcl       = 0;
                            foreach ($fclid->detail as $key => $result) {
                                if($result->commercial->status_terima == '1'){
                                    $sumterimafcl = $sumterimafcl + 1;
                                }
                            }
                            
                            if($sumterimafcl == count($fclid->detail)){
                                $fclid->status_process  = 'complete';
                                $fclid->status_terima   = '1';
                            }else{
                                $fclid->status_process  = 'process';
                                $fclid->status_terima   = '0';
                            }

                            $fclid->save();
                        }
                    }
                }

                echo goResult(true, 'Penerimaan berhasil di tambah');
                return;
            }else{
                echo goResult(false, 'Penerimaan gagal di tambah');
                return;
            }
	}

	public function penerimaan_pembelian_edit_post(){
		 $teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	     }

		 $id 					 = $this->input->post('id_penerimaan');
		 $penerimaan             = PenerimaanpembelianModel::find($id);
            if(!$penerimaan){
                echo goResult(false, 'Maaf, penerimaan tidak ada');
                return;
            }

            $rules = [
                'required'  => [
                    ['database'],['tgl_terima'],['pembelianid'],['ekspedisi'],['gudang']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $idlclflc                   = $this->input->post('id');
            $iddetail                   = $this->input->post('id_detail');
            $category                   = $this->input->post('category');
            $item                       = $this->input->post('name');
            $idbarang                   = $this->input->post('id_barang');
            $stokinput                  = $this->input->post('stok_input');
            $stokupdate                 = $this->input->post('stok_update');
            $stokterima                 = $this->input->post('stok_terima');
            $groupfcllcl                = $this->input->post('groupfcllcl');
            if(!$item){
                echo goResult(false, 'Maaf, barang belum dipilih');
                return;
            }

            $qtynull                = 0;
            for ($i=0; $i < count($item); $i++) { 
                if($stokterima[$i] <= 0){
                    $qtynull        = $qtynull + 1;
                }
            }

            if($qtynull > 0){
                echo goResult(false, 'Qty cannot be zero');
                return;
            }

            $stokerror                  = 0;
            for ($i=0; $i < count($item); $i++) { 
                $stoksisa               = $stokinput[$i] - $stokupdate[$i];
                // dd($stoksisa);
                if($stokterima[$i] > $stoksisa){
                    $stokerror          = $stokerror + 1;
                }
            }
            // dd($stokerror);
            // if($stokerror > 0){
            //     echo goResult(false, 'stokerror');
            //     return;
            // }

            //last penerimaan
            $lastpenerimaan             = PenerimaanpembeliandetailModel::where('id_penerimaanpembelian', $id)->asc()->get();
            $arridlcllast               = array();
            $arridcommerciallast        = array();
            foreach ($lastpenerimaan as $key => $value) {
                if($value->category == 'lcl'){
                    $arridlcllast[]                 = $value->id_fcl_lcl;
                    $lcldetail                      = PembelianlcldetailModel::find($value->id_detail);
                    if($lcldetail){
                        $lcldetail->qty_terima          = $lcldetail->qty_terima - $value->qty;
                        if($lcldetail->qty_terima == $lcldetail->qty){
                            $lcldetail->status_terima   = '1';
                        }else{
                            $lcldetail->status_terima   = '0';
                        }

                        if($lcldetail->save()){
                            $commercialdetail           = PenjualanfromchinaModel::find($lcldetail->id_penjualanfromchina);
                            if($commercialdetail){
                                foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                    if($valuecommercialdetail->id_restok == $lcldetail->id_restok){
                                        $commercialdetailid = PenjualanfromchinadetailModel::find($valuecommercialdetail->id);
                                        $commercialdetailid->status_terima = $lcldetail->status_terima;
                                        $commercialdetailid->save();
                                    }
                                }

                                $countterima                = 0;
                                foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                    if($commercialdetailid->status_terima == '1'){
                                        $countterima        = $countterima + 1;
                                    }
                                }

                                if($countterima == count($commercialdetail->detail)){
                                    $commercialdetail->status_terima    = '1';
                                    $commercialdetail->status           = 'complete';
                                    $commercialdetail->save();
                                }else{
                                    $commercialdetail->status_terima    = '0';
                                    $commercialdetail->status           = 'process';
                                    $commercialdetail->save();
                                }

                                $restok                     = RestokModel::find($lcldetail->id_restok);
                                if($restok){
                                    $restok->jml_datang     = $restok->jml_datang - $value->qty;
                                    if($restok->jml_datang == $restok->jml_permintaan){
                                        $restok->status         = 'complete';
                                        $restok->status_terima  = '1';
                                    }else{
                                        $restok->status         = 'requested';
                                        $restok->status_terima  = '0';
                                    }
                                    
                                    $restok->save();
                                }
                            }
                        }
                    }
                }else{
                    $commercialdetail               = PenjualanfromchinadetailModel::find($value->id_detail);
                    if($commercialdetail){
                        $commercialdetail->qty_terima   = $commercialdetail->qty_terima - $value->qty;
                        $arridcommerciallast[]          = $commercialdetail->id_penjualanfromchina;
                        if($commercialdetail->qty_terima == $commercialdetail->qty){
                            $commercialdetail->status_terima    = '1';
                        }else{
                            $commercialdetail->status_terima    = '0';
                        }

                        if($commercialdetail->save()){
                            $restok                     = RestokModel::find($commercialdetail->id_restok);
                            if($restok){
                                $restok->jml_datang     = $restok->jml_datang - $value->qty;
                                if($restok->jml_datang == $restok->jml_permintaan){
                                    $restok->status         = 'complete';
                                    $restok->status_terima  = '1';
                                }else{
                                    $restok->status         = 'requested';
                                    $restok->status_terima  = '0';
                                }

                                $restok->save();
                            }
                        }
                    }
                }
            }

            if(count($arridlcllast) > 0){
                $lcllast                = PembelianlclModel::whereIn('id', $arridlcllast)->asc()->get();
                foreach ($lcllast as $key => $result) {
                    $lclid              = PembelianlclModel::find($result->id);
                    if($lclid){
                        $sumstatusterima    = 0;
                        foreach ($lclid->detail as $key => $value) {
                            if($value->status_terima == '1'){
                                $sumstatusterima = $sumstatusterima + 1;
                            }
                        }

                        if($sumstatusterima == count($lclid->detail)){
                            $lclid->status_process  = 'complete';
                            $lclid->status_terima   = '1';
                        }else{
                            $lclid->status_process  = 'process';
                            $lclid->status_terima   = '0';
                        }

                        $lclid->save();
                    }
                }
            }

            if(count($arridcommerciallast) > 0){
                $commerciallast         = PenjualanfromchinaModel::whereIn('id', $arridcommerciallast)->asc()->get();
                foreach ($commerciallast as $key => $result) {
                    $commercialid       = PenjualanfromchinaModel::find($result->id);
                    if($commercialid){
                        $sumstatusterima    = 0;
                        foreach ($commercialid->detail as $key => $value) {
                            if($value->status_terima == '1'){
                                $sumstatusterima = $sumstatusterima + 1;
                            }
                        }

                        if($sumstatusterima == count($commercialid->detail)){
                            $commercialid->status           = 'complete';
                            $commercialid->status_terima    = '1';
                        }else{
                            $commercialid->status           = 'process';
                            $commercialid->status_terima    = '0';
                        }

                        $commercialid->save();
                    }
                }

                $fcldetaillast          = FclContainerdetailModel::whereIn('id_penjualanfromchina', $arridcommerciallast)->groupBy('id_fclcontainer')->asc()->get();
                $arridfcllast           = array();
                foreach ($fcldetaillast as $key => $value) {
                    $arridfcllast[]     = $value->id_fclcontainer;
                }

                $fcl                    = FclContainerModel::whereIn('id', $arridfcllast)->asc()->get();
                foreach ($fcl as $key => $value) {
                    $fclid              = FclContainerModel::find($value->id);
                    if($fclid){
                        $sumterimafcl       = 0;
                        foreach ($fclid->detail as $key => $result) {
                            if($result->commercial->status_terima == '1'){
                                $sumterimafcl = $sumterimafcl + 1;
                            }
                        }

                        if($sumterimafcl == count($fclid->detail)){
                            $fclid->status_process  = 'complete';
                            $fclid->status_terima   = '1';
                        }else{
                            $fclid->status_process  = 'process';
                            $fclid->status_terima   = '0';
                        }

                        $fclid->save();
                    }
                }
            }

            //update penerimaan
            $valuebarang                = explode('-', $this->input->post('pembelianid'));
            $penerimaan->name_db        = $this->input->post('database');
            $penerimaan->tgl_terima     = $this->input->post('tgl_terima');
            $penerimaan->id_ekspedisi   = $this->input->post('ekspedisi');
            $penerimaan->id_gudang      = $this->input->post('gudang');
            $penerimaan->keterangan     = $this->input->post('keterangan');
            $penerimaan->category       = $valuebarang[0];
            $penerimaan->id_fcl_lcl     = $valuebarang[1];
            if($penerimaan->save()){
                PenerimaanpembeliandetailModel::where('id_penerimaanpembelian', $id)->delete();
                $arridlcl               = array();
                $arridcommercial        = array();
                for ($i=0; $i < count($item); $i++) { 
                    $detail                             = new PenerimaanpembeliandetailModel;
                    $detail->id_penerimaanpembelian     = $penerimaan->id;
                    $detail->category                   = $category[$i];
                    $detail->id_fcl_lcl                 = $idlclflc[$i];
                    $detail->id_detail                  = $iddetail[$i];
                    $detail->id_barang                  = $idbarang[$i];
                    $detail->name                       = $item[$i];
                    $detail->qty                        = $stokterima[$i];
                    if($detail->save()){
                        if($detail->category == 'lcl'){
                            $arridlcl[]                     = $idlclflc[$i];
                            $lcldetail                      = PembelianlcldetailModel::find($iddetail[$i]);
                            if($lcldetail){
                                $lcldetail->qty_terima          = $lcldetail->qty_terima + $detail->qty;
                                if($lcldetail->qty_terima == $lcldetail->qty){
                                    $lcldetail->status_terima   = '1';
                                }else{
                                    $lcldetail->status_terima   = '0';
                                }

                                if($lcldetail->save()){
                                    $commercialdetail           = PenjualanfromchinaModel::find($lcldetail->id_penjualanfromchina);
                                    if($commercialdetail){
                                        foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                            if($valuecommercialdetail->id_restok == $lcldetail->id_restok){
                                                $commercialdetailid = PenjualanfromchinadetailModel::find($valuecommercialdetail->id);
                                                $commercialdetailid->status_terima = $lcldetail->status_terima;
                                                $commercialdetailid->save();
                                            }
                                        }

                                        $countterima                = 0;
                                        foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                            if($commercialdetailid->status_terima == '1'){
                                                $countterima        = $countterima + 1;
                                            }
                                        }

                                        if($countterima == count($commercialdetail->detail)){
                                            $commercialdetail->status_terima    = '1';
                                            $commercialdetail->status           = 'complete';
                                            $commercialdetail->save();
                                        }else{
                                            $commercialdetail->status_terima    = '0';
                                            $commercialdetail->status           = 'process';
                                            $commercialdetail->save();
                                        }

                                        $restok                     = RestokModel::find($lcldetail->id_restok);
                                        if($restok){
                                            $restok->jml_datang     = $restok->jml_datang + $detail->qty;
                                            if($restok->jml_datang == $restok->jml_permintaan){
                                                $restok->status         = 'complete';
                                                $restok->status_terima  = '1';
                                            }else{
                                                $restok->status         = 'requested';
                                                $restok->status_terima  = '0';
                                            }

                                            $restok->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            $commercialdetail               = PenjualanfromchinadetailModel::find($iddetail[$i]);
                            if($commercialdetail){
                                $commercialdetail->qty_terima   = $commercialdetail->qty_terima + $detail->qty;
                                $arridcommercial[]              = $commercialdetail->id_penjualanfromchina;
                                if($commercialdetail->qty_terima == $commercialdetail->qty){
                                    $commercialdetail->status_terima    = '1';
                                }else{
                                    $commercialdetail->status_terima    = '0';
                                }

                                if($commercialdetail->save()){
                                    $restok                     = RestokModel::find($commercialdetail->id_restok);
                                    if($restok){
                                        $restok->jml_datang     = $restok->jml_datang + $detail->qty;
                                        if($restok->jml_datang == $restok->jml_permintaan){
                                            $restok->status         = 'complete';
                                            $restok->status_terima  = '1';
                                        }else{
                                            $restok->status         = 'requested';
                                            $restok->status_terima  = '0';
                                        }

                                        $restok->save();
                                    }
                                }
                            }
                        }
                    }
                }

                if(count($arridlcl) > 0){
                    $lcl                    = PembelianlclModel::whereIn('id', $arridlcl)->asc()->get();
                    foreach ($lcl as $key => $result) {
                        $lclid              = PembelianlclModel::find($result->id);
                        if($lclid){
                            $sumstatusterima    = 0;
                            foreach ($lclid->detail as $key => $value) {
                                if($value->status_terima == '1'){
                                    $sumstatusterima = $sumstatusterima + 1;
                                }
                            }

                            if($sumstatusterima == count($lclid->detail)){
                                $lclid->status_process  = 'complete';
                                $lclid->status_terima   = '1';
                            }else{
                                $lclid->status_process  = 'process';
                                $lclid->status_terima   = '0';
                            }

                            $lclid->save();
                        }
                    }
                }

                if(count($arridcommercial) > 0){
                    $commercial             = PenjualanfromchinaModel::whereIn('id', $arridcommercial)->asc()->get();
                    foreach ($commercial as $key => $result) {
                        $commercialid       = PenjualanfromchinaModel::find($result->id);
                        if($commercialid){
                            $sumstatusterima    = 0;
                            foreach ($commercialid->detail as $key => $value) {
                                if($value->status_terima == '1'){
                                    $sumstatusterima = $sumstatusterima + 1;
                                }
                            }

                            if($sumstatusterima == count($commercialid->detail)){
                                $commercialid->status           = 'complete';
                                $commercialid->status_terima    = '1';
                            }else{
                                $commercialid->status           = 'process';
                                $commercialid->status_terima    = '0';
                            }

                            $commercialid->save();
                        }
                    }

                    $fcldetail              = FclContainerdetailModel::whereIn('id_penjualanfromchina', $arridcommercial)->groupBy('id_fclcontainer')->asc()->get();
                    $arridfcl               = array();
                    foreach ($fcldetail as $key => $value) {
                        $arridfcl[]         = $value->id_fclcontainer;
                    }

                    $fcl                    = FclContainerModel::whereIn('id', $arridfcl)->asc()->get();
                    foreach ($fcl as $key => $value) {
                        $fclid              = FclContainerModel::find($value->id);
                        if($fclid){
                            $sumterimafcl       = 0;
                            foreach ($fclid->detail as $key => $result) {
                                if($result->commercial->status_terima == '1'){
                                    $sumterimafcl = $sumterimafcl + 1;
                                }
                            }

                            if($sumterimafcl == count($fclid->detail)){
                                $fclid->status_process  = 'complete';
                                $fclid->status_terima   = '1';
                            }else{
                                $fclid->status_process  = 'process';
                                $fclid->status_terima   = '0';
                            }

                            $fclid->save();
                        }
                    }
                }

                echo goResult(true, 'Penerimaan berhasil di edit');
                return;
            }else{
                echo goResult(false, 'Penerimaan gagal di edit');
                return;
            }
	}

	public function penerimaan_pembelian_hapus_delete($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
	        // dd($id);
		    $penerimaan             = PenerimaanpembelianModel::where('kode',$id)->first();
            if(!$penerimaan){
                echo goResult(false, 'Maaf, penerimaan tidak ada');
                return;
            }

            //back stok sebelumnya
            $lastpenerimaan             = PenerimaanpembeliandetailModel::where('id_penerimaanpembelian', $id)->asc()->get();
            $arridlcllast               = array();
            $arridcommerciallast        = array();
            foreach ($lastpenerimaan as $key => $value) {
                if($value->category == 'lcl'){
                    $arridlcllast[]                 = $value->id_fcl_lcl;
                    $lcldetail                      = PembelianlcldetailModel::find($value->id_detail);
                    $lcldetail->qty_terima          = $lcldetail->qty_terima - $value->qty;
                    if($lcldetail->qty_terima == $lcldetail->qty){
                        $lcldetail->status_terima   = '1';
                    }else{
                        $lcldetail->status_terima   = '0';
                    }

                    if($lcldetail->save()){
                        $commercialdetail           = PenjualanfromchinaModel::find($lcldetail->id_penjualanfromchina);
                        if($commercialdetail){
                            foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                if($valuecommercialdetail->id_restok == $lcldetail->id_restok){
                                    $commercialdetailid = PenjualanfromchinadetailModel::find($valuecommercialdetail->id);
                                    $commercialdetailid->status_terima = $lcldetail->status_terima;
                                    $commercialdetailid->save();
                                }
                            }

                            $countterima                = 0;
                            foreach ($commercialdetail->detail as $key => $valuecommercialdetail) {
                                if($commercialdetailid->status_terima == '1'){
                                    $countterima        = $countterima + 1;
                                }
                            }

                            if($countterima == count($commercialdetail->detail)){
                                $commercialdetail->status_terima    = '1';
                                $commercialdetail->status           = 'complete';
                                $commercialdetail->save();
                            }else{
                                $commercialdetail->status_terima    = '0';
                                $commercialdetail->status           = 'process';
                                $commercialdetail->save();
                            }

                            $restok                     = RestokModel::find($lcldetail->id_restok);
                            if($restok){
                                $restok->jml_datang     = $restok->jml_datang - $value->qty;
                                if($restok->jml_datang == $restok->jml_permintaan){
                                    $restok->status         = 'complete';
                                    $restok->status_terima  = '1';
                                }elseif($restok->jml_datang > 0 && $restok->jml_datang < $restok->jml_permintaan){
                                    $restok->status         = 'process';
                                    $restok->status_terima  = '0';
                                }else{
                                    $restok->status         = 'requested';
                                    $restok->status_terima  = '0';
                                }

                                $restok->save();
                            }
                        }
                    }
                }else{
                    $commercialdetail               = PenjualanfromchinadetailModel::find($value->id_detail);
                    if($commercialdetail){
                        $commercialdetail->qty_terima   = $commercialdetail->qty_terima - $value->qty;
                        $arridcommerciallast[]          = $commercialdetail->id_penjualanfromchina;
                        if($commercialdetail->qty_terima == $commercialdetail->qty){
                            $commercialdetail->status_terima    = '1';
                        }else{
                            $commercialdetail->status_terima    = '0';
                        }

                        if($commercialdetail->save()){
                            $restok                     = RestokModel::find($commercialdetail->id_restok);
                            if($restok){
                                $restok->jml_datang     = $restok->jml_datang - $value->qty;
                                if($restok->jml_datang == $restok->jml_permintaan){
                                    $restok->status         = 'complete';
                                    $restok->status_terima  = '1';
                                }elseif($restok->jml_datang > 0 && $restok->jml_datang < $restok->jml_permintaan){
                                    $restok->status         = 'process';
                                    $restok->status_terima  = '0';
                                }else{
                                    $restok->status         = 'requested';
                                    $restok->status_terima  = '0';
                                }

                                $restok->save();
                            }
                        }
                    }
                }
            }

            if(count($arridlcllast) > 0){
                $lcllast                = PembelianlclModel::whereIn('id', $arridlcllast)->asc()->get();
                foreach ($lcllast as $key => $result) {
                    $lclid              = PembelianlclModel::find($result->id);
                    if($lclid){
                        $sumstatusterima    = 0;
                        foreach ($lclid->detail as $key => $value) {
                            if($value->status_terima == '1'){
                                $sumstatusterima = $sumstatusterima + 1;
                            }
                        }

                        if($sumstatusterima == count($lclid->detail)){
                            $lclid->status_process  = 'complete';
                            $lclid->status_terima   = '1';
                        }elseif($sumstatusterima > 0 && $sumstatusterima < count($lclid->detail)){
                            $lclid->status_process  = 'process';
                            $lclid->status_terima   = '0';
                        }else{
                            $lclid->status_process  = 'requested';
                            $lclid->status_terima   = '0';
                        }

                        $lclid->save();
                    }
                }
            }

            if(count($arridcommerciallast) > 0){
                $commerciallast         = PenjualanfromchinaModel::whereIn('id', $arridcommerciallast)->asc()->get();
                foreach ($commerciallast as $key => $result) {
                    $commercialid       = PenjualanfromchinaModel::find($result->id);
                    if($commercialid){
                        $sumstatusterima    = 0;
                        foreach ($commercialid->detail as $key => $value) {
                            if($value->status_terima == '1'){
                                $sumstatusterima = $sumstatusterima + 1;
                            }
                        }

                        if($sumstatusterima == count($commercialid->detail)){
                            $commercialid->status           = 'complete';
                            $commercialid->status_terima    = '1';
                        }elseif($sumstatusterima > 0 && $sumstatusterima < count($commercialid->detail)){
                            $commercialid->status           = 'process';
                            $commercialid->status_terima    = '0';
                        }else{
                            $commercialid->status           = 'requested';
                            $commercialid->status_terima    = '0';
                        }

                        $commercialid->save();
                    }
                }

                $fcldetaillast          = FclContainerdetailModel::whereIn('id_penjualanfromchina', $arridcommerciallast)->groupBy('id_fclcontainer')->asc()->get();
                $arridfcllast           = array();
                foreach ($fcldetaillast as $key => $value) {
                    $arridfcllast[]     = $value->id_fclcontainer;
                }

                $fcl                    = FclContainerModel::whereIn('id', $arridfcllast)->asc()->get();
                foreach ($fcl as $key => $value) {
                    $fclid              = FclContainerModel::find($value->id);
                    if($fclid){
                        $sumterimafcl       = 0;
                        foreach ($fclid->detail as $key => $result) {
                            if($result->commercial->status_terima == '1'){
                                $sumterimafcl = $sumterimafcl + 1;
                            }
                        }
                        
                        if($sumterimafcl == count($fclid->detail)){
                            $fclid->status_process  = 'complete';
                            $fclid->status_terima   = '1';
                        }else{
                            $fclid->status_process  = 'process';
                            $fclid->status_terima   = '0';
                        }

                        $fclid->save();
                    }
                }
            }
            $penerimaan->status     = '0';
            if($penerimaan->save()){
                if($penerimaan->category_import == 'noimport' && $penerimaan->category == 'lcl'){
                    foreach ($penerimaan->detail as $key => $value) {
                        $lclid                  = PembelianlclModel::find($value->id_fcl_lcl);
                        if($lclid){
                            $lclid->status_deleted  = '1';
                            $lclid->save();
                            foreach ($lclid->detail as $key => $result) {
                                $commercialinvoiceid                    = PenjualanfromchinaModel::find($result->id_penjualanfromchina);
                                if($commercialinvoiceid){
                                    $commercialinvoiceid->status_deleted    = '1';
                                    $commercialinvoiceid->save();
                                }

                                $restokid                               = RestokModel::find($result->id_restok);
                                if($restokid){
                                    $restokid->status_deleted               = '1';
                                    $restokid->save();
                                }
                            }
                        }
                    }
                }
            }

            echo goResult(true, 'Data anda berhasil dihapus');
            return;
	}

	//untuk edit view penerimaan pembelian
	public function penerimaan_viewedit_get($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }

			$data['penerimaan'] 	= PenerimaanpembelianModel::with(['gudang','ekspedisi'])->find($id);
			$data['idpenerimaan'] 	= $id;
			$data['detail'] 		= PenerimaanpembeliandetailModel::with('lcldetail','commercialdetail')->where('id_penerimaanpembelian', $id)->asc()->get();
			$data['gudang'] 			= GudangModel::where('status', '1')->asc()->get();
			$data['ekspedisi'] 			= EkspedisiModel::where('status', '1')->orderBy('name', 'asc')->get();

			if($data['penerimaan']->category == 'lcl'){
				//untuk mengambil data lcl melalui id_fcl_lcl di penerimaan
				$data['pembelianlcl'] 	= PembelianlclModel::with('detail')->where('status_deleted', '0')->where('status_terima', '0')->orWhere('id', $data['penerimaan']->id_fcl_lcl)->desc()->get();

				$id_arr_pembelianlcl = array();
			foreach ($data['pembelianlcl'] as $key => $value) {
					$id_arr_pembelianlcl[] 	= $value->id;
					

			}
			$countlcl = $data['pembelianlcl']->count();
			$data['countlcl'] = $countlcl;
			$data['pembelianlcldetail'] = PembelianlcldetailModel::whereIn('id_pembelianlcl',$id_arr_pembelianlcl)->desc()->get();

				$data['fclcontainer'] 	= FclContainerModel::where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
			}else{
				
				$data['pembelianlcl'] 	= PembelianlclModel::with('detail')->where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
				//untuk mengambil data fcl melalui id_fcl_lcl di penerimaan
				$data['fclcontainer'] 	= FclContainerModel::where('status_deleted', '0')->where('status_terima', '0')->orWhere('id', $data['penerimaan']->id_fcl_lcl)->desc()->get();

			}
			echo goResult(true,$data);
			return;
	}
	//untuk edit view penerimaan pembelian
	public function penerimaan_viewedit_baru_get($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }


			$kodeFilter 			= $this->input->get('kode');
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$checkdatevalue 		= $this->input->get('checkdatevalue');
            $lclflcFilter          = $this->input->get('lcl_fcl');
            
			
			if(!$checkdatevalue){
				$checkdatefix 	= '';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}

			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}

			if(!$tgl_awal){
		    $startDate      = date('Y-m-d', strtotime($lastDate. '-7 days'));
				
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}

			if(!$kodeFilter && !$lclflcFilter){
				$valuekode 		= '';
				$valuelclflc  	='';
			}else{
				$valuekode 		= $kodeFilter;
                $valuelclflc 	= $lclflcFilter;
            
			}
			$threeLetters = substr($lclflcFilter, 0, 4);
			
			$invoice_no_fcl = substr($lclflcFilter, 4);


			$code = PenerimaanpembelianModel::where('kode',$id)->first();
			// dd(json_encode($code));
	
					$this->db->select('penerimaan_pembelian.id as id, penerimaan_pembelian.*,');
	                $this->db->from('penerimaan_pembelian');
	                $this->db->join('pembelian_lcl', 'pembelian_lcl.id = penerimaan_pembelian.id_fcl_lcl');
	                $this->db->where('penerimaan_pembelian.kode LIKE', '%'.$valuekode.'%');
	                $this->db->where('penerimaan_pembelian.status', '1');
	             	$this->db->where('penerimaan_pembelian.id', $code->id);
	                $this->db->group_start(); // Mulai grup where
	                $this->db->or_like('pembelian_lcl.invoice', $valuelclflc); // Gunakan or_like untuk mencocokkan kondisi OR
	                $this->db->group_end(); // Akhiri grup where
	                $this->db->order_by('penerimaan_pembelian.tgl_terima', 'desc');
	                $penerimaan = $this->db->get()->result();

              
			

			$idpenerimaan 			= array();
			$idcategory 			= array();
			foreach ($penerimaan as $key => $value) {
				$idpenerimaan[] 	= $value->id;
				$idcategory[] = $value->category;

			}

			
			$data['penerimaan'] 	= PenerimaanpembelianModel::with(['ekspedisi','lcl','fcl','gudang','teknisi'])->whereIn('id', $idpenerimaan)->where('status', '1')->orderBy('tgl_terima', 'desc')->get();

			$data['detail'] 		= PenerimaanpembeliandetailModel::with('lcldetail','commercialdetail')->where('id_penerimaanpembelian',$code->id)->asc()->get();

			$id_barang_list = $data['detail']->pluck('id_barang');

			// Fetch the related barang records using whereIn
			$data['barang'] = BarangModel::whereIn('id', $id_barang_list)->get();
			
			$data['pembelianlcl']   = PembelianlclModel::with('ekspedisilcl')->where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
			$id_arr_pembelianlcl = array();
			foreach ($data['pembelianlcl'] as $key => $value) {
					$id_arr_pembelianlcl[] 	= $value->id;
					

			}
			$countlcl = $data['pembelianlcl']->count();
			$data['countlcl'] = $countlcl;
			$data['pembelianlcldetail'] = PembelianlcldetailModel::whereIn('id_pembelianlcl',$id_arr_pembelianlcl)->desc()->get();

			

			$count = $data['pembelianlcldetail']->count();
			$data['countlcldetail'] = $count;
            $data['fclcontainer']   = FclContainerModel::where('status_deleted', '0')->where('status_terima', '0')->desc()->get();
            $id_fcl_array = array();
            foreach($data['fclcontainer'] as $index => $result){
            	$id_fcl_array[] = $result->id;
            }
            $data['fclcontainerdetail'] = FclContainerdetailModel::whereIn('id_fclcontainer',$id_fcl_array)->get();

            $id_penjualanfromchina_array = array();
            foreach ($data['fclcontainerdetail'] as $index2 => $result) {
            	$id_penjualanfromchina_array[] = $result->id_penjualanfromchina;
            }
            $data['penjualanfromchina_detail'] = PenjualanfromchinadetailModel::whereIn('id_penjualanfromchina',$id_penjualanfromchina_array)->get();
            
            $tot_penjualanfromchina_detail = [];
			$sum_tot = [];
			$sum_qty_terima = [];
			$tot_remaining_qty = []; // New array for the result of $tot_penjualanfromchina_detail - $sum_qty_terima

			foreach ($data['penjualanfromchina_detail'] as $index => $result) {
			    $id = $result['id_penjualanfromchina'];

			    // Check if the current id is already in the arrays
			    if (array_key_exists($id, $tot_penjualanfromchina_detail)) {
			        // If it's a duplicate, sum up the qty and qty_terima
			        $tot_penjualanfromchina_detail[$id] += $result['qty'];
			        $sum_qty_terima[$id] += $result['qty_terima'];
			    } else {
			        // If it's not a duplicate, initialize the qty and qty_terima
			        $tot_penjualanfromchina_detail[$id] = $result['qty'];
			        $sum_qty_terima[$id] = $result['qty_terima'];
			    }
			}

			// Now create the $tot_remaining_qty array by subtracting $sum_qty_terima from $tot_penjualanfromchina_detail
			foreach ($tot_penjualanfromchina_detail as $id => $qty) {
			    $tot_remaining_qty[$id] = $qty - (isset($sum_qty_terima[$id]) ? $sum_qty_terima[$id] : 0);
			}

			// Store the results back in the $data array
			$data['sum_tot'] = $tot_penjualanfromchina_detail;
			$data['sum_qty_terima'] = $sum_qty_terima;
			$data['tot_remaining_qty'] = $tot_remaining_qty;

			$data['gudang'] 			= GudangModel::where('status', '1')->asc()->get();
			$data['ekspedisi'] 			= EkspedisiModel::where('status', '1')->orderBy('name', 'asc')->get();
			$data['matauang']			= MatauangModel::where('status','1')->get();
			$data['tgl_awal'] 		= $startDate;
			$data['tgl_akhir'] 		= $lastDate;
			$data['checkdatevalue'] = $checkdatefix;
			$data['kodeFilter'] 	= $valuekode;
            $data['lclflcFilter']     = $valuelclflc;
            echo goResult(true, $data);
            return;
	}

	//untuk edit view menambahkan produk di tabel
	public function penerimaan_add_invoice_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$rules      = [
                'required'  => [
                    ['invoice']
                ]
            ];

            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }

            $id                             = $this->input->post('id');
            
            $penerimaanpembelian            = PenerimaanpembelianModel::find($id);
            if(!$penerimaanpembelian){
                echo goResult(false, 'Penerimaan pembelian not found');
                return;
            }

            $penerimaanpembelian->invoicebee = $this->input->post('invoice');
            if($penerimaanpembelian->save()){
                echo goResult(true, 'Succes update no.pembelian bee');
                return;
            }else{
                echo goResult(false, 'Invoice error');
                return; 
            }
	}

	//untuk view pindah gudang
	public function penerimaan_pindah_gudang_get(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$kodeFilter 			= $this->input->get('kode');
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$checkdatevalue 		= $this->input->get('checkdatevalue');

			if(!$checkdatevalue){
				$checkdatefix 	= 'checked';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}
			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}
			if(!$tgl_awal){
				$startDate 		= date('Y-m-d', strtotime($lastDate. '-7 days'));
				// $startDate =PenerimaanpindahgudangModel::orderBy('tgl_terima', 'asc')->value('tgl_terima');
				// $startDate 		= PenerimaanpindahgudangModel::orderBy('tgl_terima', 'asc')->value('tgl_terima');
				// dd($startDate);
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}

			if(!$kodeFilter){
				$valuekode 			= '';
			}else{
				$valuekode 			= $kodeFilter;
			}
			if($checkdatefix == 'checked'){
				$penerimaan 			= PenerimaanpindahgudangModel::where('kode', 'like', '%'.$valuekode.'%')->whereDate('tgl_terima', '>=', $startDate)->whereDate('tgl_terima', '<=', $lastDate)->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}else{
				$penerimaan 			= PenerimaanpindahgudangModel::where('kode', 'like', '%'.$valuekode.'%')->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}
			$idpenerimaan 			= array();
			foreach ($penerimaan as $key => $value) {
				$idpenerimaan[] 	= $value->id;
			}
		
			$total					= count($penerimaan);
			$data['penerimaan'] 	= PenerimaanpindahgudangModel::with('detail')->whereIn('id', $idpenerimaan)->where('status', '1')->desc()->get();
			$data_teknisi_name		= TeknisiModel::where('status_regis',1)->pluck('name');
			$data_teknisi = TeknisiModel::where('status_regis', 1)->get(); // Ambil semua data teknisi yang terdaftar

			$data['teknisi'] = [];
				foreach ($data_teknisi as $key => $value) {
					$data['teknisi'][] = [
						'id' => $value->id,
						'name' => $value->name
					];
				}
			$data['tgl_awal'] 		= $startDate;
			$data['tgl_akhir'] 		= $lastDate;
			$data['checkdatevalue'] = $checkdatefix;
			$data['kodeFilter'] 	= $valuekode;
			// $pindahgudangPT 		= PindahgudangModel::with('detail')->where('code', 'LIKE', '%PT%')->where('status_deleted', '0')->where('status_kirim', '1')->where('status_bee', '1')->where('status_terima', '0')->orderBy('id', 'desc')->get();

			// $idgudangPTBarang = array(); // Initialize an empty array to store id_barang

			// foreach ($pindahgudangPT as $key => $value) {
			//     // $value->detail is a collection, so we need to iterate through it
			//     foreach ($value->detail as $detailItem) {
			//         $idgudangPTBarang[] = $detailItem->id_barang; // Access id_barang from each detail item
			//     }
			// }
			// $barang = BarangModel::whereIn('id', $idgudangPTBarang)
			// ->orderByRaw("FIELD(id, " . implode(',', $idgudangPTBarang) . ")")
			// ->get();
			// $data['barangPT'] = $barang;
			
				
			
			$data['pindahgudangPT'] = PindahgudangModel::with(['detail' => function ($query) {
				$query->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id')
				->select('pindahgudang_detail.*', 'barang.id as barang_id', 'barang.name as barang_nama');
			}])
			->where('code', 'LIKE', '%PT%')
			->where('status_deleted', '0')
			->where('status_kirim', '1')
			->where('status_bee', '1')
			
			->where('status_terima', '0')
			->orderBy('id', 'desc')
			->get();

			$data['pindahgudangUD'] = PindahgudangModel::with(['detail' => function ($query) {
				$query->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id')
				->select('pindahgudang_detail.*', 'barang.id as barang_id', 'barang.name as barang_nama');
			}])
			->where('code', 'LIKE', '%UD%')
			->where('status_deleted', '0')
			->where('status_kirim', '1')
			
			->where('status_bee', '1')
			->where('status_terima', '0')
			->orderBy('id', 'desc')
			->get();

			// $data['pindahgudangUD'] 		= PindahgudangModel::where('code', 'LIKE', '%UD%')->where('status_deleted', '0')->where('status_kirim', '1')->where('status_bee', '1')->where('status_terima', '0')->orderBy('id', 'desc')->get();
			echo goResult(true, $data);
			return;
	}

	//untuk menambahkan data pindah gudang
	public function penerimaan_pindah_gudang_create_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
		    
			$rules = [
	                'required'  => [
	                    ['database'],['tgl_terima']
	                ]
	        ];
            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }
            $item                       = $this->input->post('id_pindahgudangdetail');
            $stokterima                 = $this->input->post('stok_terima');
            if(!$item){
                echo goResult(false, 'Maaf, barang belum dipilih');
                return;
            }
            $qtynull                = 0;
            for ($i=0; $i < count($item); $i++) { 
                if($stokterima[$i] <= 0){
                    $qtynull        = $qtynull + 1;
                }
            }
            if($qtynull > 0){
                echo goResult(false, 'Qty cannot be zero');
                return;
            }
            if($this->input->post('database') == 'PT'){
                $codepindahgudang       = PenerimaanpindahgudangModel::where('name_db', 'PT')->desc()->first();
                if(!$codepindahgudang){
                    $isToday            = explode('-', date('Y-m-d'));
                    $isYear             = $isToday[0];
                    $year               = substr($isYear, -2);
                    $month              = $isToday[1];
                    $day                = $isToday[2];
                    $newcodepindah      = 'STPGPT-'.$year.''.$month.'-001';
                }else{
                    $today              = explode(' ', $codepindahgudang->created_at);
                    $dateToday          = substr($today[0], 0, -3);
                    $allpenjualan       = PenerimaanpindahgudangModel::where('name_db', 'PT')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
                    if($dateToday == date('Y-m')){
                        $year                   = substr(date('Y'), -2);
                        $newcode                = count($allpenjualan) + 1;
                        if($newcode > 0 && $newcode <= 9){
                            $newSelectioncode   = 'STPGPT-'.$year.''.date('m').'-00'.$newcode;
                        }elseif($newcode > 9 && $newcode <= 99){
                            $newSelectioncode   = 'STPGPT-'.$year.''.date('m').'-0'.$newcode;
                        }else{
                            $newSelectioncode   = 'STPGPT-'.$year.''.date('m').'-'.$newcode;
                        }
                        $lastSelection          = PenerimaanpindahgudangModel::where('kode', $newSelectioncode)->get();
                        if(count($lastSelection) > 0){
                            $newcode2           = $newcode + 1;
                            if($newcode2 > 0 && $newcode2 <= 9){
                                $newcodepindah  = 'STPGPT-'.$year.''.date('m').'-00'.$newcode2;
                            }elseif($newcode2 > 9 && $newcode2 <= 99){
                                $newcodepindah  = 'STPGPT-'.$year.''.date('m').'-0'.$newcode2;
                            }else{
                                $newcodepindah  = 'STPGPT-'.$year.''.date('m').'-'.$newcode2;
                            }
                        }else{
                            $newcodepindah      = $newSelectioncode;
                        }
                    }else{
                        $isToday            = explode('-', date('Y-m-d'));
                        $isYear             = $isToday[0];
                        $year               = substr($isYear, -2);
                        $month              = $isToday[1];
                        $day                = $isToday[2];
                        $newcodepindah      = 'STPGPT-'.$year.''.$month.'-001';
                    }
                }
            }else{
                $codepindahgudang       = PenerimaanpindahgudangModel::where('name_db', 'UD')->desc()->first();
                if(!$codepindahgudang){
                    $isToday            = explode('-', date('Y-m-d'));
                    $isYear             = $isToday[0];
                    $year               = substr($isYear, -2);
                    $month              = $isToday[1];
                    $day                = $isToday[2];
                    $newcodepindah      = 'STPGUD-'.$year.''.$month.'-001';
                }else{
                    $today              = explode(' ', $codepindahgudang->created_at);
                    $dateToday          = substr($today[0], 0, -3);
                    $allpenjualan       = PenerimaanpindahgudangModel::where('name_db', 'UD')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
                    if($dateToday == date('Y-m')){
                        $year                   = substr(date('Y'), -2);
                        $newcode                = count($allpenjualan) + 1;
                        if($newcode > 0 && $newcode <= 9){
                            $newSelectioncode   = 'STPGUD-'.$year.''.date('m').'-00'.$newcode;
                        }elseif($newcode > 9 && $newcode <= 99){
                            $newSelectioncode   = 'STPGUD-'.$year.''.date('m').'-0'.$newcode;
                        }else{
                            $newSelectioncode   = 'STPGUD-'.$year.''.date('m').'-'.$newcode;
                        }
                        $lastSelection          = PenerimaanpindahgudangModel::where('kode', $newSelectioncode)->get();
                        if(count($lastSelection) > 0){
                            $newcode2           = $newcode + 1;
                            if($newcode2 > 0 && $newcode2 <= 9){
                                $newcodepindah  = 'STPGUD-'.$year.''.date('m').'-00'.$newcode2;
                            }elseif($newcode2 > 9 && $newcode2 <= 99){
                                $newcodepindah  = 'STPGUD-'.$year.''.date('m').'-0'.$newcode2;
                            }else{
                                $newcodepindah  = 'STPGUD-'.$year.''.date('m').'-'.$newcode2;
                            }
                        }else{
                            $newcodepindah      = $newSelectioncode;
                        }
                    }else{
                        $isToday            = explode('-', date('Y-m-d'));
                        $isYear             = $isToday[0];
                        $year               = substr($isYear, -2);
                        $month              = $isToday[1];
                        $day                = $isToday[2];
                        $newcodepindah      = 'STPGUD-'.$year.''.$month.'-001';
                    }
                }
            }
            $stokerror                  = 0;
            for ($i=0; $i < count($item); $i++) { 
                $cekstok                = PindahgudangdetailModel::find($item[$i]);
                $stoksisa               = $cekstok->stok_input - $cekstok->stok_terima;
                if($stokterima[$i] > $stoksisa){
                    $stokerror          = $stokerror + 1;
                }
            }
            if($stokerror > 0){
                echo goResult(false, 'Maaf, input stok terima tidak boleh melebihi stok input atau sisa stok');
                return;
            }
            $newpindahgudang                = new PenerimaanpindahgudangModel;
            $newpindahgudang->kode          = $newcodepindah;
            $newpindahgudang->name_db       = $this->input->post('database');
            $newpindahgudang->tgl_terima    = $this->input->post('tgl_terima');
            $newpindahgudang->id_teknisi    = $teknisi->id;
            $newpindahgudang->status        = '1';
            if($newpindahgudang->save()){
                for ($i=0; $i < count($item); $i++) { 
                    $detail                             = new PenerimaanpindahgudangdetailModel;
                    $detail->id_penerimaanpindahgudang  = $newpindahgudang->id;
                    $detail->id_pindahgudangdetail      = $item[$i];
                    $detail->qty                        = $stokterima[$i];
                    $detail->save();
                    $pindahdetail                       = PindahgudangdetailModel::find($item[$i]);
                    $pindahdetail->stok_terima          = $pindahdetail->stok_terima + $stokterima[$i];
                    $pindahdetail->save();
                }
                $pindahgudangdetail         = PindahgudangdetailModel::whereIn('id', $item)->groupBy('id_pindahgudang')->get();
                $idPindahgudang             = array();
                foreach ($pindahgudangdetail as $key => $value) {
                    $idPindahgudang[]       = $value->id_pindahgudang;
                }
                $pindahgudang               = PindahgudangModel::whereIn('id', $idPindahgudang)->asc()->get();
                foreach ($pindahgudang as $key => $value) {
                    $sumstokinput           = 0;
                    $sumstokterima          = 0;
                    foreach ($value->detail as $key => $result) {
                        $sumstokinput       = $sumstokinput + $result->stok_input;
                        $sumstokterima      = $sumstokterima + $result->stok_terima;
                    }
                    if($sumstokinput == $sumstokterima){
                        $pindahgudangid                 = PindahgudangModel::find($value->id);
                        $pindahgudangid->status_terima  = '1';
                        $pindahgudangid->save();
                    }
                }
                echo goResult(true, 'Penerimaan berhasil di tambah');
                return;
            }else{
                echo goResult(false, 'Penerimaan gagal di tambah');
                return;
            }

	}

	//untuk view edit pindah gudang
	public function penerimaan_pindah_gudang_viewedit_get($id){
		
		$data['penerimaan']     = PenerimaanpindahgudangModel::where('kode',$id)->first();
		 
            
		if ($data['penerimaan']) {
		    // Mengambil detail penerimaan terkait
		    $data['detail'] = PenerimaanpindahgudangdetailModel::where('id_penerimaanpindahgudang', $data['penerimaan']->id)->orderBy('id', 'asc')->get();
		    
		    // Inisialisasi array untuk menyimpan id dari detail
		    $detailIds = [];

		    // Menambahkan setiap id ke array $detailIds
		    foreach ($data['detail'] as $key => $value) {
		        $detailIds[] = $value['id_pindahgudangdetail']; // Menggunakan array assignment untuk menambah id ke dalam array
		    }
		 
		}
		// if($data['penerimaan']->name_db=='PT'){
			$data['pindahgudangPTFilter'] = PindahgudangModel::select('pindahgudang.*', 'barang.id as barang_id', 'barang.name as barang_nama','pindahgudang_detail.stok_input','pindahgudang_detail.stok_terima')
		    ->join('pindahgudang_detail', 'pindahgudang.id', '=', 'pindahgudang_detail.id_pindahgudang') // Inner join dengan detail
		    ->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id') // Inner join dengan barang
		    ->where('pindahgudang.code', 'LIKE', '%PT%')
		    
		    ->whereIn('pindahgudang_detail.id', $detailIds) // Menggunakan whereIn untuk mencocokkan id dari detail
		    // ->orderBy('pindahgudang.id', 'desc')
		    ->get();

		// }else{
			$data['pindahgudangUDFilter'] = PindahgudangModel::select('pindahgudang.*', 'barang.id as barang_id', 'barang.name as barang_nama','pindahgudang_detail.stok_input','pindahgudang_detail.stok_terima')
		    ->join('pindahgudang_detail', 'pindahgudang.id', '=', 'pindahgudang_detail.id_pindahgudang') // Inner join dengan detail
		    ->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id') // Inner join dengan barang
		    ->where('pindahgudang.code', 'LIKE', '%UD%')
		    
		    ->whereIn('pindahgudang_detail.id', $detailIds) // Menggunakan whereIn untuk mencocokkan id dari detail
		    // ->orderBy('pindahgudang.id', 'desc')
		    ->get();			
		// }
		

        $data['pindahgudangPT'] = PindahgudangModel::with(['detail' => function ($query) {
				$query->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id')
				->select('pindahgudang_detail.*', 'barang.id as barang_id', 'barang.name as barang_nama');
			}])
			->where('code', 'LIKE', '%PT%')
			->where('status_deleted', '0')
			->where('status_kirim', '1')
			->where('status_bee', '1')
			
			->where('status_terima', '0')
			->orderBy('id', 'desc')
			->get();

			$data['pindahgudangUD'] = PindahgudangModel::with(['detail' => function ($query) {
				$query->join('barang', 'pindahgudang_detail.id_barang', '=', 'barang.id')
				->select('pindahgudang_detail.*', 'barang.id as barang_id', 'barang.name as barang_nama');
			}])
			->where('code', 'LIKE', '%UD%')
			->where('status_deleted', '0')
			->where('status_kirim', '1')
			
			->where('status_bee', '1')
			->where('status_terima', '0')
			->orderBy('id', 'desc')
			->get();

			// $data['pindahgudangUD'] 		= PindahgudangModel::where('code', 'LIKE', '%UD%')->where('status_deleted', '0')->where('status_kirim', '1')->where('status_bee', '1')->where('status_terima', '0')->orderBy('id', 'desc')->get();
			echo goResult(true, $data);
			return;
         echo goResult(true, $data);
         return;
	}

	public function penerimaan_pindah_gudang_edit_post(){
			$id = $this->input->post('id_penerimaangudang');
			$newpindahgudang                = PenerimaanpindahgudangModel::find($id);
            if(!$newpindahgudang){
                echo goResult(false, 'Maaf, penerimaan tidak ada');
                return;
            }
            $rules = [
                'required'  => [
                    ['database'],['tgl_terima']
                ]
            ];
            $validate   = Validation::check($rules,'post');
            if(!$validate->auth){
                echo goResult(false,$validate->msg);
                return;
            }
            //back stok sebelumnya
            $backitem                               = array();
            foreach ($newpindahgudang->detail as $key => $value) {
                $backitem[]                         = $value->id_pindahgudangdetail;
                $pindahdetail                       = PindahgudangdetailModel::find($value->id_pindahgudangdetail);
                $pindahdetail->stok_terima          = $pindahdetail->stok_terima - $value->qty;
                $pindahdetail->save();
            }
            $pindahgudangdetailback     = PindahgudangdetailModel::whereIn('id', $backitem)->groupBy('id_pindahgudang')->get();
            $idPindahgudang             = array();
            foreach ($pindahgudangdetailback as $key => $value) {
                $idPindahgudang[]       = $value->id_pindahgudang;
            }
            $pindahgudangback           = PindahgudangModel::whereIn('id', $idPindahgudang)->asc()->get();
            foreach ($pindahgudangback as $key => $value) {
                $sumstokinput           = 0;
                $sumstokterima          = 0;
                foreach ($value->detail as $key => $result) {
                    $sumstokinput       = $sumstokinput + $result->stok_input;
                    $sumstokterima      = $sumstokterima + $result->stok_terima;
                }
                $pindahgudangid                     = PindahgudangModel::find($value->id);
                if($sumstokinput == $sumstokterima){
                    $pindahgudangid->status_terima  = '1';
                }else{
                    $pindahgudangid->status_terima  = '0';
                }
                $pindahgudangid->save();
            }
            //new stok
            $item                       = $this->input->post('id_pindahgudangdetail'); //untuk barang menggunakan id barang
            $stokterima                 = $this->input->post('stok_terima');
            if(!$item){
                echo goResult(false, 'Maaf, barang belum dipilih');
                return;
            }
            $qtynull                = 0;
            for ($i=0; $i < count($item); $i++) { 
                if($stokterima[$i] <= 0){
                    $qtynull        = $qtynull + 1;
                }
            }
            if($qtynull > 0){
                echo goResult(false, 'Qty cannot be zero');
                return;
            }
            $stokerror                  = 0;
            for ($i=0; $i < count($item); $i++) { 
                $cekstok                = PindahgudangdetailModel::find($item[$i]);
                $stoksisa               = $cekstok->stok_input - $cekstok->stok_terima;
                if($stokterima[$i] > $stoksisa){
                    $stokerror          = $stokerror + 1;
                }
            }
            if($stokerror > 0){
                echo goResult(false, 'Maaf, input stok terima tidak boleh melebihi stok input atau sisa stok');
                return;
            }
            $newpindahgudang->tgl_terima    = $this->input->post('tgl_terima');
            $newpindahgudang->name_db       = $this->input->post('database');
            if($newpindahgudang->save()){
                PenerimaanpindahgudangdetailModel::where('id_penerimaanpindahgudang', $id)->delete();
                for ($i=0; $i < count($item); $i++) { 
                    $detail                             = new PenerimaanpindahgudangdetailModel;
                    $detail->id_penerimaanpindahgudang  = $newpindahgudang->id;
                    $detail->id_pindahgudangdetail      = $item[$i];
                    $detail->qty                        = $stokterima[$i];
                    $detail->save();
                    $pindahdetail                       = PindahgudangdetailModel::find($item[$i]);
                    $pindahdetail->stok_terima          = $pindahdetail->stok_terima + $stokterima[$i];
                    $pindahdetail->save();
                }
                $pindahgudangdetail         = PindahgudangdetailModel::whereIn('id', $item)->groupBy('id_pindahgudang')->get();
                $idPindahgudang             = array();
                foreach ($pindahgudangdetail as $key => $value) {
                    $idPindahgudang[]       = $value->id_pindahgudang;
                }
                $pindahgudang               = PindahgudangModel::whereIn('id', $idPindahgudang)->asc()->get();
                foreach ($pindahgudang as $key => $value) {
                    $sumstokinput           = 0;
                    $sumstokterima          = 0;
                    foreach ($value->detail as $key => $result) {
                        $sumstokinput       = $sumstokinput + $result->stok_input;
                        $sumstokterima      = $sumstokterima + $result->stok_terima;
                    }
                    if($sumstokinput == $sumstokterima){
                        $pindahgudangid                 = PindahgudangModel::find($value->id);
                        $pindahgudangid->status_terima  = '1';
                        $pindahgudangid->save();
                    }
                }
                echo goResult(true, 'Penerimaan berhasil di edit');
                return;
            }else{
                echo goResult(false, 'Penerimaan gagal di edit');
                return;
            }
	}

	public function penerimaan_pindah_gudang_hapus_delete($id){
			$penerimaan 			= PenerimaanpindahgudangModel::where('kode',$id)->first();
			if(!$penerimaan){
				echo goResult(false, 'Maaf, penerimaan tidak ada');
				return;
			}
			//back stok sebelumnya
			$backitem 								= array();
			foreach ($penerimaan->detail as $key => $value) {
				$backitem[] 						= $value->id_pindahgudangdetail;
				$pindahdetail 						= PindahgudangdetailModel::find($value->id_pindahgudangdetail);
				$pindahdetail->stok_terima 			= $pindahdetail->stok_terima - $value->qty;
				$pindahdetail->save();
			}
			$pindahgudangdetailback 	= PindahgudangdetailModel::whereIn('id', $backitem)->groupBy('id_pindahgudang')->get();
			$idPindahgudang 			= array();
			foreach ($pindahgudangdetailback as $key => $value) {
				$idPindahgudang[] 		= $value->id_pindahgudang;
			}
			$pindahgudangback 			= PindahgudangModel::whereIn('id', $idPindahgudang)->asc()->get();
			foreach ($pindahgudangback as $key => $value) {
				$sumstokinput 			= 0;
				$sumstokterima 			= 0;
				foreach ($value->detail as $key => $result) {
					$sumstokinput 		= $sumstokinput + $result->stok_input;
					$sumstokterima 		= $sumstokterima + $result->stok_terima;
				}
				$pindahgudangid 					= PindahgudangModel::find($value->id);
				if($sumstokinput == $sumstokterima){
					$pindahgudangid->status_terima 	= '1';
				}else{
					$pindahgudangid->status_terima 	= '0';
				}
				$pindahgudangid->save();
			}
			$penerimaan->status 	= '0';
			$penerimaan->save();
			echo goResult(true, 'Data anda berhasil dihapus');
			return;
	}


	public function penerimaan_barang_lain_get(){
		$teknisi = $this->validate_teknisi_id();
	    if (!$teknisi) {
	            return; // Stop further execution if validation fails
	    }

		$kodeFilter 			= $this->input->get('kode');
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$checkdatevalue 		= $this->input->get('checkdatevalue');
			if(!$checkdatevalue){
				$checkdatefix 	= 'checked';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}
			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}
			if(!$tgl_awal){
				// $startDate 		= date('Y-m-d', strtotime($lastDate. '-7 days')); 
				$startDate 		= PenerimaanbaranglainModel::orderBy('tgl_terima', 'asc')->value('tgl_terima');
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}
			if(!$kodeFilter){
				$valuekode 			= '';
			}else{
				$valuekode 			= $kodeFilter;
			}
			if($checkdatefix == 'checked'){
				$penerimaan 	= PenerimaanbaranglainModel::where('kode', 'like', '%'.$valuekode.'%')->whereDate('tgl_terima', '>=', $startDate)->whereDate('tgl_terima', '<=', $lastDate)->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}else{
				$penerimaan 	= PenerimaanbaranglainModel::where('kode', 'like', '%'.$valuekode.'%')->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}
			$idpenerimaan 			= array();
			foreach ($penerimaan as $key => $value) {
				$idpenerimaan[] 	= $value->id;
			}
			
			
			$total					= count($penerimaan);
			$data['penerimaan'] 	= PenerimaanbaranglainModel::with('teknisi')->whereIn('id', $idpenerimaan)->where('status', '1')->desc()->get();
			
			$data['tgl_awal'] 		= $startDate;
			$data['tgl_akhir'] 		= $lastDate;
			$data['checkdatevalue'] = $checkdatefix;
			$data['kodeFilter'] 	= $valuekode;

			echo goResult(true,$data);
			return;
	}

	public function penerimaan_barang_lain_editview_get($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$data['type'] 			= 'update';
			$data['penerimaan'] 	= PenerimaanbaranglainModel::where('kode',$id)->first();

			$data['detail'] 		= PenerimaanbaranglaindetailModel::where('id_penerimaanbaranglain', $data['penerimaan']->id)->asc()->get();
			if(!$data){
				echo goResult(false,'Data Tidak Ada');
				return;
			}
			echo goResult(true,$data);
			return;
	}

	public function penerimaan_barang_lain_edit_post(){

		$teknisi = $this->validate_teknisi_id();
	    if (!$teknisi) {
	        return; // Stop further execution if validation fails
	    }
		$id = $this->input->post('id');
		$name 						= $this->input->post('name');
		// dd($id);
		$newpindahbaranglain 		= PenerimaanbaranglainModel::find($id);
			if(!$newpindahbaranglain){
				echo goResult(false, 'Maaf, penerimaan tidak ada');
				return;
			}
			$rules = [
				'required' 	=> [
					['tgl_terima'],['pengirim']
				]
			];
			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}
			
			

			$qty 						= $this->input->post('qty');
			$keterangan 				= $this->input->post('keteranganbarang');
			if(!$name){
				echo goResult(false, 'Maaf, barang belum dipilih');
				return;
			}
			$namenull 				= 0;
			$qtynull 				= 0;
			for ($i=0; $i < count($name); $i++) { 
				if($name[$i] == ''){
					$namenull 		= $namenull + 1;
				}
				if($qty[$i] <= 0){
					$qtynull 		= $qtynull + 1;
				}
			}
			if($namenull > 0){
				echo goResult(false, 'Nama barang is required');
				return;
			}
			if($qtynull > 0){
				echo goResult(false, 'Qty cannot be zero');
				return;
			}
			$newpindahbaranglain->tgl_terima 	= $this->input->post('tgl_terima');
			$newpindahbaranglain->pengirim 		= $this->input->post('pengirim');
			$newpindahbaranglain->keterangan 	= $this->input->post('keterangan');
			if($newpindahbaranglain->save()){
				PenerimaanbaranglaindetailModel::where('id_penerimaanbaranglain', $id)->delete();
				for ($i=0; $i < count($name); $i++) { 
					$detail 							= new PenerimaanbaranglaindetailModel;
					$detail->id_penerimaanbaranglain 	= $newpindahbaranglain->id;
					$detail->name 						= $name[$i];
					$detail->qty 						= $qty[$i];
					$detail->keterangan 				= $keterangan[$i];
					$detail->save();
				}
				echo goResult(true, 'Penerimaan berhasil di edit');
				return;
			}else{
				echo goResult(false, 'Penerimaan gagal di edit');
				return;
			}
	}

	public function penerimaan_barang_lain_create_post(){
			$teknisi = $this->validate_teknisi_id();
				    if (!$teknisi) {
				        return; // Stop further execution if validation fails
				    }
			$rules = [
				'required' 	=> [
					['tgl_terima'],['pengirim']
				]
			];
			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}
			$name 						= $this->input->post('name');
			$qty 						= $this->input->post('qty');
			$keterangan 				= $this->input->post('keteranganbarang');
			if(!$name){
				echo goResult(false, 'Maaf, barang belum dipilih');
				return;
			}
			$namenull 				= 0;
			$qtynull 				= 0;
			for ($i=0; $i < count($name); $i++) { 
				if($name[$i] == ''){
					$namenull 		= $namenull + 1;
				}
				if($qty[$i] <= 0){
					$qtynull 		= $qtynull + 1;
				}
			}
			if($namenull > 0){
				echo goResult(false, 'Nama barang is required');
				return;
			}
			if($qtynull > 0){
				echo goResult(false, 'Qty cannot be zero');
				return;
			}
			$codebaranglain 		= PenerimaanbaranglainModel::desc()->first();
			if(!$codebaranglain){
				$isToday 			= explode('-', date('Y-m-d'));
				$isYear 			= $isToday[0];
				$year 				= substr($isYear, -2);
				$month 				= $isToday[1];
				$day 				= $isToday[2];
				$newcodepindah 		= 'TTBRG-'.$year.''.$month.'-001';
			}else{
				$today 				= explode(' ', $codebaranglain->created_at);
				$dateToday 			= substr($today[0], 0, -3);
				$allpenjualan 		= PenerimaanbaranglainModel::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
				if($dateToday == date('Y-m')){
					$year 					= substr(date('Y'), -2);
					$newcode 				= count($allpenjualan) + 1;
					if($newcode > 0 && $newcode <= 9){
						$newSelectioncode 	= 'TTBRG-'.$year.''.date('m').'-00'.$newcode;
					}elseif($newcode > 9 && $newcode <= 99){
						$newSelectioncode 	= 'TTBRG-'.$year.''.date('m').'-0'.$newcode;
					}else{
						$newSelectioncode 	= 'TTBRG-'.$year.''.date('m').'-'.$newcode;
					}
					$lastSelection 			= PenerimaanbaranglainModel::where('kode', $newSelectioncode)->get();
					if(count($lastSelection) > 0){
						$newcode2 			= $newcode + 1;
						if($newcode2 > 0 && $newcode2 <= 9){
							$newcodepindah 	= 'TTBRG-'.$year.''.date('m').'-00'.$newcode2;
						}elseif($newcode2 > 9 && $newcode2 <= 99){
							$newcodepindah 	= 'TTBRG-'.$year.''.date('m').'-0'.$newcode2;
						}else{
							$newcodepindah 	= 'TTBRG-'.$year.''.date('m').'-'.$newcode2;
						}
					}else{
						$newcodepindah 		= $newSelectioncode;
					}
				}else{
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodepindah 		= 'TTBRG-'.$year.''.$month.'-001';
				}
			}
			$newpindahbaranglain 				= new PenerimaanbaranglainModel;
			$newpindahbaranglain->kode 			= $newcodepindah;
			$newpindahbaranglain->tgl_terima 	= $this->input->post('tgl_terima');
			$newpindahbaranglain->pengirim 		= $this->input->post('pengirim');
			$newpindahbaranglain->keterangan 	= $this->input->post('keterangan');
			$newpindahbaranglain->id_teknisi 	= $teknisi->id;
			$newpindahbaranglain->status 		= '1';
			if($newpindahbaranglain->save()){
				for ($i=0; $i < count($name); $i++) { 
					$detail 							= new PenerimaanbaranglaindetailModel;
					$detail->id_penerimaanbaranglain 	= $newpindahbaranglain->id;
					$detail->name 						= $name[$i];
					$detail->qty 						= $qty[$i];
					$detail->keterangan 				= $keterangan[$i];
					$detail->save();
				}
				echo goResult(true, 'Penerimaan berhasil di tambah');
				return;
			}else{
				echo goResult(false, 'Penerimaan gagal di tambah');
				return;
			}
	}

	public function penerimaan_barang_lain_hapus_delete($id){
			$penerimaan 			= PenerimaanbaranglainModel::where('kode',$id)->first();
			if(!$penerimaan){
				echo goResult(false, 'Maaf, penerimaan tidak ada');
				return;
			}
			$penerimaan->status 	= '0';
			$penerimaan->save();
			echo goResult(true, 'Data anda berhasil dihapus');
			return;
	}

	public function penerimaan_barang_lain_print_get($id){
			$teknisi = $this->validate_teknisi_id();
				    if (!$teknisi) {
				        return; // Stop further execution if validation fails
				    }
			$data['teknisi'] = TeknisiModel::where('id',$teknisi->id)->first();
			$data['penerimaan'] 	= PenerimaanbaranglainModel::where('kode',$id)->first();
			$data['detail'] 		= PenerimaanbaranglaindetailModel::where('id_penerimaanbaranglain', $data['penerimaan']->id)->asc()->get();
			$perpage 				= count($data['detail']) / 5;
			$data['numberofpage'] 	= ceil($perpage); //pembulatan ke atas
			if(!$data['penerimaan']){
				echo goResult(false,'Data Tidak Ditemukan');
				return;
			}
			echo goResult(true,$data);
			return;
	}

	public function penerimaan_dokumen_get(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }

			$kodeFilter 			= $this->input->get('kode');
			$tgl_awal 				= $this->input->get('tgl_awal');
			$tgl_akhir 				= $this->input->get('tgl_akhir');
			$checkdatevalue 		= $this->input->get('checkdatevalue');
			if(!$checkdatevalue){
				$checkdatefix 	= 'checked';
			}else{
				$checkdatefix 	= $checkdatevalue;
			}
			if(!$tgl_akhir){
				$lastDate 		= date('Y-m-d');
			}else{
				if($tgl_akhir == ''){
					$lastDate 	= date('Y-m-d');
				}else{
					$lastDate 	= $tgl_akhir;
				}
			}
			if(!$tgl_awal){
				// $startDate 		= date('Y-m-d', strtotime($lastDate. '-7 days'));
				$startDate 		= PenerimaandokumenModel::orderBy('tgl_terima', 'asc')->value('tgl_terima');
			}else{
				if($tgl_awal == ''){
					$startDate 	= date('Y-m-d', strtotime($lastDate. '-7 days'));
				}else{
					$startDate 	= $tgl_awal;
				}
			}
			if(!$kodeFilter){
				$valuekode 			= '';
			}else{
				$valuekode 			= $kodeFilter;
			}
			if($checkdatefix == 'checked'){
				$penerimaan 	= PenerimaandokumenModel::where('kode', 'like', '%'.$valuekode.'%')->whereDate('tgl_terima', '>=', $startDate)->whereDate('tgl_terima', '<=', $lastDate)->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}else{
				$penerimaan 	= PenerimaandokumenModel::where('kode', 'like', '%'.$valuekode.'%')->where('status', '1')->orderBy('tgl_terima', 'desc')->get();
			}
			$idpenerimaan 			= array();
			foreach ($penerimaan as $key => $value) {
				$idpenerimaan[] 	= $value->id;
			}
			
			$total					= count($penerimaan);
			$data['penerimaan'] 	= PenerimaandokumenModel::with('teknisi')->whereIn('id', $idpenerimaan)->where('status', '1')->desc()->get();
			
			$data['tgl_awal'] 		= $startDate;
			$data['tgl_akhir'] 		= $lastDate;
			$data['checkdatevalue'] = $checkdatefix;
			$data['kodeFilter'] 	= $valuekode;
			echo goResult(true,$data);
			return;		
	}

	public function penerimaan_dokumen_viewedit_get($id){
			$teknisi = $this->validate_teknisi_id();
			if (!$teknisi) {
		            return; // Stop further execution if validation fails
		        }

			$data['type'] 			= 'update';
			$data['penerimaan'] 	= PenerimaandokumenModel::where('kode',$id)->first();
			$data['detail'] 		= PenerimaandokumendetailModel::where('id_penerimaandokumen', $data['penerimaan']->id)->asc()->get();
			if(!$data['penerimaan']){
				echo goResult(false,'Data tidak ada');
				return;
			}
			echo goResult(true,$data);
			return;
	}

	public function penerimaan_dokumen_tambah_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }

			$rules = [
				'required' 	=> [
					['tgl_terima'],['pengirim']
				]
			];
			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}
			$nodokumen 				= $this->input->post('nodokumen');
			if(!$nodokumen){
				echo goResult(false, 'Maaf, nomor dokumen tidak ada');
				return;
			}
			$fielderror 			= 0;
			for ($i=0; $i < count($nodokumen); $i++) { 
				if($nodokumen[$i] == ''){
					$fielderror 	= $fielderror + 1;
				}
			}
			if($fielderror > 0){
				echo goResult(false, 'No. Dokumen is required');
				return;
			}
			$codebaranglain 		= PenerimaandokumenModel::desc()->first();
			if(!$codebaranglain){
				$isToday 			= explode('-', date('Y-m-d'));
				$isYear 			= $isToday[0];
				$year 				= substr($isYear, -2);
				$month 				= $isToday[1];
				$day 				= $isToday[2];
				$newcodepindah 		= 'TTDOK-'.$year.''.$month.'-001';
			}else{
				$today 				= explode(' ', $codebaranglain->created_at);
				$dateToday 			= substr($today[0], 0, -3);
				$allpenjualan 		= PenerimaandokumenModel::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->get();
				if($dateToday == date('Y-m')){
					$year 					= substr(date('Y'), -2);
					$newcode 				= count($allpenjualan) + 1;
					if($newcode > 0 && $newcode <= 9){
						$newSelectioncode 	= 'TTDOK-'.$year.''.date('m').'-00'.$newcode;
					}elseif($newcode > 9 && $newcode <= 99){
						$newSelectioncode 	= 'TTDOK-'.$year.''.date('m').'-0'.$newcode;
					}else{
						$newSelectioncode 	= 'TTDOK-'.$year.''.date('m').'-'.$newcode;
					}
					$lastSelection 			= PenerimaandokumenModel::where('kode', $newSelectioncode)->get();
					if(count($lastSelection) > 0){
						$newcode2 			= $newcode + 1;
						if($newcode2 > 0 && $newcode2 <= 9){
							$newcodepindah 	= 'TTDOK-'.$year.''.date('m').'-00'.$newcode2;
						}elseif($newcode2 > 9 && $newcode2 <= 99){
							$newcodepindah 	= 'TTDOK-'.$year.''.date('m').'-0'.$newcode2;
						}else{
							$newcodepindah 	= 'TTDOK-'.$year.''.date('m').'-'.$newcode2;
						}
					}else{
						$newcodepindah 		= $newSelectioncode;
					}
				}else{
					$isToday 			= explode('-', date('Y-m-d'));
					$isYear 			= $isToday[0];
					$year 				= substr($isYear, -2);
					$month 				= $isToday[1];
					$day 				= $isToday[2];
					$newcodepindah 		= 'TTDOK-'.$year.''.$month.'-001';
				}
			}
			$newdokumen 				= new PenerimaandokumenModel;
			$newdokumen->kode 			= $newcodepindah;
			$newdokumen->tgl_terima 	= $this->input->post('tgl_terima');
			$newdokumen->pengirim 		= $this->input->post('pengirim');
			$newdokumen->keterangan 	= $this->input->post('keterangan');
			$newdokumen->id_teknisi 	= $teknisi->id;
			$newdokumen->status 		= '1';
			if($newdokumen->save()){
				for ($i=0; $i < count($nodokumen); $i++) { 
					$detail 						= new PenerimaandokumendetailModel;
					$detail->id_penerimaandokumen 	= $newdokumen->id;
					$detail->no_dokumen 			= $nodokumen[$i];
					$detail->save();
				}
				echo goResult(true, 'Penerimaan berhasil di tambah');
				return;
			}else{
				echo goResult(false, 'Penerimaan gagal di tambah');
				return;
			}
	}

	public function penerimaan_dokumen_edit_post(){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$id = $this->input->post('id');
			
			$newdokumen 		= PenerimaandokumenModel::find($id);
			if(!$newdokumen){
				echo goResult(false, 'Maaf, penerimaan tidak ada');
				return;
			}
			$rules = [
				'required' 	=> [
					['tgl_terima'],['pengirim']
				]
			];
			$validate 	= Validation::check($rules,'post');
			if(!$validate->auth){
				echo goResult(false,$validate->msg);
				return;
			}
			$nodokumen 				= $this->input->post('nodokumen');
			if(!$nodokumen){
				echo goResult(false, 'Maaf, nomor dokumen tidak ada');
				return;
			}
			$fielderror 			= 0;
			for ($i=0; $i < count($nodokumen); $i++) { 
				if($nodokumen[$i] == ''){
					$fielderror 	= $fielderror + 1;
				}
			}
			if($fielderror > 0){
				echo goResult(false, 'No. Dokumen is required');
				return;
			}
			$newdokumen->tgl_terima 	= $this->input->post('tgl_terima');
			$newdokumen->pengirim 		= $this->input->post('pengirim');
			$newdokumen->keterangan 	= $this->input->post('keterangan');
			if($newdokumen->save()){
				PenerimaandokumendetailModel::where('id_penerimaandokumen', $id)->delete();
				for ($i=0; $i < count($nodokumen); $i++) { 
					$detail 						= new PenerimaandokumendetailModel;
					$detail->id_penerimaandokumen 	= $newdokumen->id;
					$detail->no_dokumen 			= $nodokumen[$i];
					$detail->save();
				}
				echo goResult(true, 'Penerimaan berhasil di edit');
				return;
			}else{
				echo goResult(false, 'Penerimaan gagal di edit');
				return;
			}
	}

	public function penerimaan_dokumen_hapus_delete($id){
			$teknisi = $this->validate_teknisi_id();
	        if (!$teknisi) {
	            return; // Stop further execution if validation fails
	        }
			$penerimaan 			= PenerimaandokumenModel::where('kode',$id)->first();
			if(!$penerimaan){
				echo goResult(false, 'Maaf, penerimaan tidak ada');
				return;
			}
			$penerimaan->status 	= '0';
			$penerimaan->save();
			echo goResult(true, 'Data anda berhasil dihapus');
			return;
	}



	public function updateRestokLiveNotAjax()

	{ //cronjobnotajax



		// Fetch all id_barang from RestokModel grouped by id_barang



		$restokLive = $this->db->select('id_barang')



			->from('restok')



			->group_by('id_barang')



			->order_by('id_barang', 'asc')



			->get()



			->result_array();







		$idBarang = array_column($restokLive, 'id_barang');







		// Fetch total stok for each id_barang



		$newStok = $this->db->select('id_barang, SUM(stok) as total_stok')



			->from('barang_stok')



			->where_in('id_barang', $idBarang)



			->group_by('id_barang')



			->order_by('id_barang', 'asc')



			->get()



			->result_array();







		// Prepare bulk update data



		$updateData = [];



		foreach ($newStok as $stok) {



			$updateData[$stok['id_barang']] = $stok['total_stok'];

		}







		// Fetch all RestokModel entries that need to be updated



		$restokToUpdate = $this->db->select('*')



			->from('restok')



			->where_in('id_barang', array_keys($updateData))



			->get()



			->result();







		// Update in batches using transaction



		$this->db->trans_start();



		try {



			foreach ($restokToUpdate as $restok) {



				if (isset($updateData[$restok->id_barang])) {



					$restok->last_stok = $updateData[$restok->id_barang];



					$this->db->where('id', $restok->id)



						->update('restok', ['last_stok' => $restok->last_stok]);

				}

			}



			$this->db->trans_complete();

		} catch (\Exception $e) {



			$this->db->trans_rollback();



			throw $e;

		}







		return;

	}















	//API POST Cek Stok By Produk



	public function cek_produk_stok_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id                 = (array) $this->input->post('id');



		$gudang             = $this->input->post('gudang');







		$category           = $this->input->post('category');







		if ($category == 'all') {







			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} elseif (in_array('allproduk', $id)) {







				$product = BarangModel::where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product    = BarangModel::whereIn('id', $id)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		} else {



			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} elseif (in_array('allproduk', $id)) {







				$product = BarangModel::where('id_category', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product     = BarangModel::whereIn('id', $id)->where('id_category', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		}











		//PT







		$masterItemPT       = ApiBee::getMasterItemPT();







		$forStokPT          = ApiBee::getStokPT();







		$forGudangPT        = ApiBee::getGudangPT();















		$arrayIdPT          = array();







		$arrayStokPT        = array();







		if (!$gudang) {















			foreach ($forStokPT['data'] as $valueStokPT) {







				foreach ($product as $key => $value) {







					if ($valueStokPT->itemid == $value->new_kode) {







						foreach ($forGudangPT['data'] as $valueGudangPT) {







							if ($valueGudangPT->id == $valueStokPT->wh_id) {















								$arrayIdPT[]        = $value->id;







								$arrayStokPT[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangPT->name,







									'stok_gudang'   => number_format($valueStokPT->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokPT['data'] as $valueStokPT) {







					if ($valueStokPT->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokPT->itemid == $value->new_kode) {







								foreach ($forGudangPT['data'] as $valueGudangPT) {







									if ($valueGudangPT->id == $valueStokPT->wh_id) {















										$arrayIdPT[]        = $value->id;







										$arrayStokPT[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangPT->name,







											'stok_gudang'   => number_format($valueStokPT->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}















		//UD







		$formasterItemUD    = ApiBee::getMasterItemUD();







		$forStokUD          = ApiBee::getStokUD();







		$forGudangUD        = ApiBee::getGudangUD();















		$arrayIdUD          = array();







		$arrayStokUD        = array();







		if (!$gudang) {















			foreach ($forStokUD['data'] as $valueStokUD) {







				foreach ($product as $key => $value) {







					if ($valueStokUD->itemid == $value->new_kode) {







						foreach ($forGudangUD['data'] as $valueGudangUD) {







							if ($valueGudangUD->id == $valueStokUD->wh_id) {















								$arrayIdUD[]        = $value->id;







								$arrayStokUD[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangUD->name,







									'stok_gudang'   => number_format($valueStokUD->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokUD['data'] as $valueStokUD) {







					if ($valueStokUD->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokUD->itemid == $value->new_kode) {







								foreach ($forGudangUD['data'] as $valueGudangUD) {







									if ($valueGudangUD->id == $valueStokUD->wh_id) {















										$arrayIdUD[]        = $value->id;







										$arrayStokUD[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangUD->name,







											'stok_gudang'   => number_format($valueStokUD->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}











		$data['auth']           = true;







		$data['msg']            = 'success';







		$data['product']        = $product;







		$data['productPT']      = BarangModel::whereIn('id', $arrayIdPT)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['productUD']      = BarangModel::whereIn('id', $arrayIdUD)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['produk_sum'] = BarangModel::whereIn('id', $arrayIdPT)



			->where('status_deleted', '0')



			->groupBy('id')



			->orderBy('name', 'asc')



			->union(



				BarangModel::whereIn('id', $arrayIdUD)



					->where('status_deleted', '0')



					->groupBy('id')



					->orderBy('name', 'asc')



			)



			->get();



		$data['stokproduct']    = $arrayStokPT;







		$data['stokproductUD']  = $arrayStokUD;







		$data['countStokPT']    = count($arrayStokPT);







		$data['countStokUD']    = count($arrayStokUD);







		echo json_encode($data);







		// }



	}

	public function cek_produk_stok_get(){
		$id                 = 'allproduk';


		$category           = '1';







		if ($category == 'all') {







			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} elseif (in_array('allproduk', $id)) {







				$product = BarangModel::where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product    = BarangModel::whereIn('id', $id)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		} else {



			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}  else {







				$product     = BarangModel::where('id_category', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		}

		//PT
		$masterItemPT       = ApiBee::getMasterItemPT();
		$forStokPT          = ApiBee::getStokPT();
		$forGudangPT        = ApiBee::getGudangPT();
		$arrayIdPT          = array();
		$arrayStokPT        = array();


			foreach ($forStokPT['data'] as $valueStokPT) {


				foreach ($product as $key => $value) {

					if ($valueStokPT->itemid == $value->new_kode) {

						foreach ($forGudangPT['data'] as $valueGudangPT) {


							if ($valueGudangPT->id == $valueStokPT->wh_id) {


								// $arrayIdPT[]        = $value->id;


								// $arrayStokPT[]      = array(

								// 	'idproduct'     => $value->id,

								// 	'name'          => $value->name,

								// 	'name_gudang'   => $valueGudangPT->name,

								// 	'stok_gudang'   => number_format($valueStokPT->qty)

								// );
								$idProduct = $value->id;
			                    $name = $value->name;

			                    // Cek apakah idproduct sudah ada di $arrayStokPT
			                    if (isset($arrayStokPT[$idProduct])) {
			                        // Jika sudah ada, tambahkan stok_gudang
			                        $arrayStokPT[$idProduct]['stok_gudang'] += $valueStokPT->qty;
			                    } else {
			                        // Jika belum ada, tambahkan data baru
			                        $arrayStokPT[$idProduct] = array(
			                            'idproduct'   => $idProduct,
			                            'name'        => $name,
			                            'name_gudang' => $valueGudangPT->name,
			                            'stok_gudang' => $valueStokPT->qty
			                        );
			                    }

							}

						}

					} 

				}

			}

		foreach ($product as $key => $value) {
		    // Ambil semua idproduct yang sudah ada di $arrayStokPT
		    $existingIds = array_column($arrayStokPT, 'idproduct');
		    
		    // Cek apakah id dari $value sudah ada di $existingIds
		    if (!in_array($value->id, $existingIds)) {
		        $arrayStokPT[] = array(
		            'idproduct'    => $value->id,
		            'name'         => $value->name,
		            
		            'stok_gudang'  => 0
		        );
		    }
		}
		usort($arrayStokPT, function ($a, $b) {
		    return strcmp($a['name'], $b['name']);
		});
		

		//UD

		$formasterItemUD    = ApiBee::getMasterItemUD();
		$forStokUD          = ApiBee::getStokUD();
		$forGudangUD        = ApiBee::getGudangUD();
		$arrayIdUD          = array();
		$arrayStokUD        = array();
			foreach ($forStokUD['data'] as $valueStokUD) {

				foreach ($product as $key => $value) {

					if ($valueStokUD->itemid == $value->new_kode) {

						foreach ($forGudangUD['data'] as $valueGudangUD) {

							if ($valueGudangUD->id == $valueStokUD->wh_id) {

								$arrayIdUD[]        = $value->id;

								$arrayStokUD[]      = array(

									'idproduct'     => $value->id,
									'name'          => $value->name,
									'name_gudang'   => $valueGudangUD->name,
									'stok_gudang'   => number_format($valueStokUD->qty)

								);

							}

						}

					} else {

					}

				}

			}

		$data['auth']           = true;
		$data['msg']            = 'success';

		$data['stokproduct']    = $arrayStokPT;

		// $data['stokproductUD']  = $arrayStokUD;

		$data['countStokPT']    = count($arrayStokPT);


		$data['countStokUD']    = count($arrayStokUD);


		echo json_encode($data);

	}





	//API POST Cek Stok By Subkategori



	public function cek_subkategori_stok_post()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id                 = (array) $this->input->post('id');







		$gudang             = $this->input->post('gudang');







		$category           = $this->input->post('category');







		if ($category == 'all') {







			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} elseif (in_array('allproduk', $id)) {







				$product = BarangModel::where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product    = BarangModel::whereIn('id', $id)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		} else {



			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} elseif (in_array('allproduk', $id)) {







				$product = BarangModel::where('id_subcategory', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product     = BarangModel::whereIn('id', $id)->where('id_subcategory', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		}











		//PT







		$masterItemPT       = ApiBee::getMasterItemPT();







		$forStokPT          = ApiBee::getStokPT();







		$forGudangPT        = ApiBee::getGudangPT();















		$arrayIdPT          = array();







		$arrayStokPT        = array();







		if (!$gudang) {















			foreach ($forStokPT['data'] as $valueStokPT) {







				foreach ($product as $key => $value) {







					if ($valueStokPT->itemid == $value->new_kode) {







						foreach ($forGudangPT['data'] as $valueGudangPT) {







							if ($valueGudangPT->id == $valueStokPT->wh_id) {















								$arrayIdPT[]        = $value->id;







								$arrayStokPT[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangPT->name,







									'stok_gudang'   => number_format($valueStokPT->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokPT['data'] as $valueStokPT) {







					if ($valueStokPT->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokPT->itemid == $value->new_kode) {







								foreach ($forGudangPT['data'] as $valueGudangPT) {







									if ($valueGudangPT->id == $valueStokPT->wh_id) {















										$arrayIdPT[]        = $value->id;







										$arrayStokPT[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangPT->name,







											'stok_gudang'   => number_format($valueStokPT->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}















		//UD







		$formasterItemUD    = ApiBee::getMasterItemUD();







		$forStokUD          = ApiBee::getStokUD();







		$forGudangUD        = ApiBee::getGudangUD();















		$arrayIdUD          = array();







		$arrayStokUD        = array();







		if (!$gudang) {















			foreach ($forStokUD['data'] as $valueStokUD) {







				foreach ($product as $key => $value) {







					if ($valueStokUD->itemid == $value->new_kode) {







						foreach ($forGudangUD['data'] as $valueGudangUD) {







							if ($valueGudangUD->id == $valueStokUD->wh_id) {















								$arrayIdUD[]        = $value->id;







								$arrayStokUD[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangUD->name,







									'stok_gudang'   => number_format($valueStokUD->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokUD['data'] as $valueStokUD) {







					if ($valueStokUD->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokUD->itemid == $value->new_kode) {







								foreach ($forGudangUD['data'] as $valueGudangUD) {







									if ($valueGudangUD->id == $valueStokUD->wh_id) {















										$arrayIdUD[]        = $value->id;







										$arrayStokUD[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangUD->name,







											'stok_gudang'   => number_format($valueStokUD->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}











		$data['auth']           = true;







		$data['msg']            = 'success';







		$data['product']        = $product;







		$data['productPT']      = BarangModel::whereIn('id', $arrayIdPT)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['productUD']      = BarangModel::whereIn('id', $arrayIdUD)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['produk_sum'] = BarangModel::whereIn('id', $arrayIdPT)



			->where('status_deleted', '0')



			->groupBy('id')



			->orderBy('name', 'asc')



			->union(



				BarangModel::whereIn('id', $arrayIdUD)



					->where('status_deleted', '0')



					->groupBy('id')



					->orderBy('name', 'asc')



			)



			->get();



		$data['stokproduct']    = $arrayStokPT;







		$data['stokproductUD']  = $arrayStokUD;







		$data['countStokPT']    = count($arrayStokPT);







		$data['countStokUD']    = count($arrayStokUD);







		echo json_encode($data);

	}







	//API POST Cek Stok By Perusahaan



	public function cek_stok_post()



	{







		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id                 = (array) $this->input->post('id');







		$gudang             = $this->input->post('gudang');







		$category           = $this->input->post('category');







		if ($category == 'all') {







			if (!$id) {







				$product     = BarangModel::whereNull('id')->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product    = BarangModel::whereIn('id', $id)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		} else {



			if (!$id) {







				$product     = BarangModel::where('id_category', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			} else {







				$product     = BarangModel::whereIn('id', $id)->where('id_category', $category)->where('status_deleted', '0')->orderBy('name', 'asc')->get();

			}

		}











		//PT







		$masterItemPT       = ApiBee::getMasterItemPT();







		$forStokPT          = ApiBee::getStokPT();







		$forGudangPT        = ApiBee::getGudangPT();















		$arrayIdPT          = array();







		$arrayStokPT        = array();







		if (!$gudang) {















			foreach ($forStokPT['data'] as $valueStokPT) {







				foreach ($product as $key => $value) {







					if ($valueStokPT->itemid == $value->new_kode) {







						foreach ($forGudangPT['data'] as $valueGudangPT) {







							if ($valueGudangPT->id == $valueStokPT->wh_id) {















								$arrayIdPT[]        = $value->id;







								$arrayStokPT[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangPT->name,







									'stok_gudang'   => number_format($valueStokPT->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokPT['data'] as $valueStokPT) {







					if ($valueStokPT->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokPT->itemid == $value->new_kode) {







								foreach ($forGudangPT['data'] as $valueGudangPT) {







									if ($valueGudangPT->id == $valueStokPT->wh_id) {















										$arrayIdPT[]        = $value->id;







										$arrayStokPT[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangPT->name,







											'stok_gudang'   => number_format($valueStokPT->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}















		//UD







		$formasterItemUD    = ApiBee::getMasterItemUD();







		$forStokUD          = ApiBee::getStokUD();







		$forGudangUD        = ApiBee::getGudangUD();















		$arrayIdUD          = array();







		$arrayStokUD        = array();







		if (!$gudang) {















			foreach ($forStokUD['data'] as $valueStokUD) {







				foreach ($product as $key => $value) {







					if ($valueStokUD->itemid == $value->new_kode) {







						foreach ($forGudangUD['data'] as $valueGudangUD) {







							if ($valueGudangUD->id == $valueStokUD->wh_id) {















								$arrayIdUD[]        = $value->id;







								$arrayStokUD[]      = array(







									'idproduct'     => $value->id,







									'name'          => $value->name,







									'name_gudang'   => $valueGudangUD->name,







									'stok_gudang'   => number_format($valueStokUD->qty)







								);

							}

						}

					} else {

					}

				}

			}

		} else {















			for ($i = 0; $i < count($gudang); $i++) {















				$explodeid  = explode('-', $gudang[$i]);







				$valueid    = (int) $explodeid[1];







				foreach ($forStokUD['data'] as $valueStokUD) {







					if ($valueStokUD->wh_id == $valueid) {







						foreach ($product as $key => $value) {







							if ($valueStokUD->itemid == $value->new_kode) {







								foreach ($forGudangUD['data'] as $valueGudangUD) {







									if ($valueGudangUD->id == $valueStokUD->wh_id) {















										$arrayIdUD[]        = $value->id;







										$arrayStokUD[]      = array(







											'idproduct'     => $value->id,







											'name'          => $value->name,







											'name_gudang'   => $valueGudangUD->name,







											'stok_gudang'   => number_format($valueStokUD->qty)







										);

									}

								}

							} else {

							}

						}

					}

				}

			}

		}







		$data['auth'] 			= true;







		$data['msg'] 			= 'success';







		$data['product'] 		= $product;







		$data['productPT'] 		= BarangModel::whereIn('id', $arrayIdPT)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['productUD'] 		= BarangModel::whereIn('id', $arrayIdUD)->where('status_deleted', '0')->groupBy('id')->orderBy('name', 'asc')->get();;







		$data['stokproduct'] 	= $arrayStokPT;







		$data['stokproductUD'] 	= $arrayStokUD;







		$data['countStokPT'] 	= count($arrayStokPT);







		$data['countStokUD'] 	= count($arrayStokUD);











		echo json_encode($data);

	}



	//End API POST Cek Stok











	//API POST Cek Opname



	public function cek_opname_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		if ($_SERVER['REQUEST_METHOD'] == 'POST') {



			// Definisikan rules untuk validasi



			$rules = [



				'required' => [



					['gudang'], ['jenis'], ['sort']



				]



			];







			// Lakukan validasi menggunakan rules yang telah ditentukan



			$validate = Validation::check($rules, 'post');







			// Cek apakah validasi berhasil atau tidak



			if (!$validate->auth) {



				// Jika validasi gagal, kirim respons dengan pesan error



				echo goResult(false, $validate->msg);



				return;

			}







			// Ambil data dari request POST



			$gudang = $_POST['gudang'];



			$jenis = $_POST['jenis'];



			$sort = $_POST['sort'];







			// Proses sesuai logika bisnis yang telah ditetapkan



			if ($gudang == '7') {



				$rak = $_POST['rak'];



				if (!$rak) {



					echo goResult(false, 'Rak is required');



					return;

				}



				if ($jenis == 'all') {



					$product = BarangModel::where('status_deleted', '0')->whereIn('kode_rak', $rak)->get();

				} else {



					$product = BarangModel::where('status_deleted', '0')->where('id_category', $jenis)->whereIn('kode_rak', $rak)->get();

				}

			} else {



				$rak = '';



				if ($jenis == 'all') {



					$product = BarangModel::where('status_deleted', '0')->get();

				} else {



					$product = BarangModel::where('status_deleted', '0')->where('id_category', $jenis)->get();

				}

			}







			$id_product = [];



			foreach ($product as $key => $value) {



				$id_product[] = $value->id;

			}







			$fixproduct = [];



			$barangstok = BarangstokModel::where('id_gudang', $gudang)->whereIn('id_barang', $id_product)->asc()->get();







			foreach ($barangstok as $key => $value) {



				if ($gudang == '7') {



					$rakcode = ($value->product->kode_rak == NULL || $value->product->kode_rak == '' || $value->product->kode_rak == '-') ? '' : $value->product->rak->code;

				} else {



					$rakcode = '';

				}







				$fixproduct[] = [



					'kode_rak' => $rakcode,



					'kode_barang' => $value->product->new_kode,



					'nama_barang' => $value->product->name,



					'tipe' => $value->product->categoryitem->name,



					'stok' => $value->stok



				];

			}







			if ($gudang == '7') {



				if ($sort == 'nama_barang') {



					array_multisort(array_column($fixproduct, "nama_barang"), SORT_ASC, $fixproduct);

				} else {



					array_multisort(array_column($fixproduct, "kode_rak"), SORT_ASC, $fixproduct);

				}

			} else {



				array_multisort(array_column($fixproduct, "nama_barang"), SORT_ASC, $fixproduct);

			}







			// Siapkan data yang akan dikirim sebagai respons



			$data['auth'] = true;



			$data['msg'] = 'success';



			$data['gudang'] = $gudang;



			$data['jenis'] = $jenis;



			$data['sort'] = $sort;



			$data['rak'] = $rak;



			$data['opnameproduct'] = $fixproduct;







			// Konversi data ke format JSON dan kirim sebagai respons



			echo json_encode($data);

		} else {



			// Jika bukan request POST, kirim respons error



			echo json_encode(['error' => 'Method not allowed']);

		}

	}







	//API GET Data Opname



	public function pilihopname_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Ambil data gudang dengan status 1 secara berurutan



		$gudang = GudangModel::where('status', '1')->get();







		// Ambil data jenis barang dengan status 1 secara berurutan



		$jenis = BarangcategoryModel::where('status', '1')->get();



		$sort = array('Nama Barang', 'Rak');



		// Persiapkan respon JSON



		$response['success'] = true;



		$response['data']['gudang'] = $gudang;



		$response['data']['jenis'] = $jenis;



		$response['data']['sort'] = $sort;







		// Keluarkan respon JSON



		echo json_encode($response);



		return;

	}











	//API Get Pelaporan Service



	//API GET Service



	public function service_get()



	{



		$teknisi_id = $this->session->userdata('teknisi_id');







		// Jika teknisi ID tidak tersedia dalam session, coba ambil dari permintaan POST



		if (!$teknisi_id) {



			$teknisi_id = $this->input->post('teknisi_id');

		}







		// Jika teknisi ID tidak tersedia dari kedua sumber, berikan respons error



		if (!$teknisi_id) {



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		// Cari teknisi berdasarkan teknisi ID



		$teknisi = TeknisiModel::find($teknisi_id);







		// Periksa apakah teknisi ditemukan



		if (!$teknisi) {



			// Teknisi tidak ditemukan, kirim respons error



			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$this->data['teknisi'] = $teknisi;







		$StatusTeknisi 			= $this->data['teknisi']->status;







		$IdTeknisi 				= $this->data['teknisi']->id;







		$all_teknisi 	= TeknisiModel::where('status_regis', 1)->where('status', 'teknisi')->desc()->get();







		$biaya 			= BiayaserviceModel::asc()->get();











		$tgl_awal 				= $this->input->get('tgl_awal');







		$tgl_akhir 				= $this->input->get('tgl_akhir');







		$kodeFilter 			= $this->input->get('kode');







		$projectFilter 			= $this->input->get('project');







		$companyFilter 			= $this->input->get('company');







		$checkdatevalue 		= $this->input->get('checkdatevalue');







		if (!$checkdatevalue) {



			// var_dump('aa');



			$checkdatefix 	= 'checked';

		} else {



			$checkdatefix 	= $checkdatevalue;

		}















		if (!$tgl_akhir) {







			$lastDate 		= date('Y-m-d');

		} else {







			if ($tgl_akhir == '') {







				$lastDate 	= date('Y-m-d');

			} else {







				// var_dump('a');



				$lastDate 	= $tgl_akhir;

			}

		}















		if (!$tgl_awal) {







			$startDate 		= date('Y-m-d', strtotime($lastDate . '-7 days'));

		} else {







			if ($tgl_awal == '') {







				$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

			} else {







				$startDate 	= $tgl_awal;

			}

		}















		if (!$kodeFilter) {



			// var_dump('aa'.$kodeFilter);



			$valuekode 			= '';







			$valuekodereturn 	= '';

		} else {







			$valuekode 			= substr($kodeFilter, 1);







			$valuekodereturn 	= $kodeFilter;

		}















		if (!$projectFilter) {



			$valueproject 	= 'all';



			// var_dump($valueproject);







		} else {







			$valueproject 	= $projectFilter;



			// var_dump('a'.$projectFilter);



		}















		if (!$companyFilter) {



			// var_dump('a');



			$valuecompany 	= 'all';

		} else {







			$valuecompany 	= $companyFilter;

		}















		if ($checkdatefix == 'checked') {















			if ($valueproject != 'all') {







				if ($valueproject == 'install') {







					$projectservice = 2;

				} else {







					$projectservice = 3;

				}

			}















			if ($valueproject != 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} elseif ($valueproject != 'all' && $valuecompany == 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_projects', $projectservice)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_projects', $projectservice)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} elseif ($valueproject == 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_customernew', $valuecompany)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} else {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pelaporan', '>=', $startDate)->whereDate('tgl_pelaporan', '<=', $lastDate)->where('status', 0)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			}

		} else {



















			if ($valueproject != 'all') {







				if ($valueproject == 'install') {







					$projectservice = 2;

				} else {







					$projectservice = 3;

				}

			}















			if ($valueproject != 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} elseif ($valueproject != 'all' && $valuecompany == 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_projects', $projectservice)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_projects', $projectservice)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} elseif ($valueproject == 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_customernew', $valuecompany)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			} else {







				// var_dump($valueproject);



				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pelaporan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 0)->orderBy('tgl_pelaporan', 'desc')->get();

				}

			}

		}















		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}















		$idService 					= array();



		// var_dump($service);



		foreach ($service as $value) {







			$idService[] 			= $value->id;

		}















		$paginate					= new Myweb_pagination;















		$total						= count($idService);







		$service 			= ServiceModel::whereIn('id', $idService)->desc()->get();







		$numberpage 		= $page * 20;







		$pagination 		= $paginate->paginate(base_url('teknisi/service/' . $this->data['teknisi']->username . '/page/'), 6, 20, $total, $page);







		// $customer_new	= 











		$tgl_awal 			= $startDate;







		$tgl_akhir 			= $lastDate;







		$checkdatevalue 	= $checkdatefix;







		$kodeFilter 		= $valuekodereturn;







		$projectFilter 		= $valueproject;







		$companyFilter 		= $valuecompany;



		$jenis_projects = array();



		$customernew = array();



		foreach ($service as $key => $result) {



			if ($result->id_projects == 2) {







				$jenis_projects[] = $result->projects->name;

			} elseif ($result->id_projects == 3) {



				$jenis_projects[] = $result->projects->name;

			}



			$customernew[] = CustomernewModel::where('status_deleted', 0)->where('id', $result->id_customernew)->orderBy('nama_perusahaan', 'asc')->get();

		}











		$data = array(



			'service' => $service, // Sesuaikan dengan data yang Anda peroleh dari logika Anda



			// 'projects' =>$jenis_projects,



			'customer' => $customernew,



			'biaya' => $biaya



			// Anda bisa menambahkan data lainnya yang ingin Anda sertakan dalam respons



		);



		echo json_encode($data);

	}







	//API Get Detail Service Pelaporan



	public function service_pelaporan_detail_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}







		$id = $this->input->get('id');







		$data['serviceimage'] 	= ServiceimageModel::where('id_service', $id)->where('kategori', 'service')->desc()->get();







		$data['all_teknisi'] 	= TeknisiModel::where('status_regis', 1)->where('status', 'teknisi')->desc()->get();























		$data['barang'] 		= BarangModel::where('status_deleted', '0')->desc()->get();







		$data['projects'] 		= ProjectsModel::where('id', '!=', 1)->asc()->get();











		$service 				= ServiceModel::find($id);



		$cabang 		= CabangModel::where('status', '1')->where('id', $service->id_cabang)->asc()->get();



		$biaya 			= BiayaserviceModel::where('id', $service->biaya)->asc()->get();



		$customer_new	= CustomernewModel::where('status_deleted', 0)->where('id', $service->id_customernew)->orderBy('nama_perusahaan', 'asc')->get();



		$nama_customer = array();



		$jenis_projects = array();



		$barang = array();



		$kode = array();



		$customerData = array();



		foreach ($customer_new as $customer) {



			// $nama_customer = $customer->nama_perusahaan;



			$customerData = $customer->toArray();

		}







		$historygroup 	= ServicehistoryModel::where('id_service', $service->id)->groupBy('no_spk')->asc()->get();







		$historyall 	= ServicehistoryModel::where('id_service', $service->id)->asc()->get();















		$arrbaranginstall 		= array();







		$barangInstall 			= BaranginstallModel::where('id_service', $id)->get();















		if ($service->id_projects == 2) {







			$jenis_projects[] = $service->projects->name;



			foreach ($service->baranginstall as $resultBarang) {



				$barang[] = $resultBarang->barang->name;



				$kode[] = 'I' . $service->kode;

			}

		} elseif ($service->id_projects == 3) {



			$jenis_projects[] = $service->projects->name;



			$barang[] = $service->barang->name;



			$kode[] = 'S' . $service->kode;

		}



		// dd($service->customernew->name_perusahaan);



		$data['barangInstall'] 	= $arrbaranginstall;











		$data_json = array(



			'service' => $service,



			'kode' => $kode,



			'customer_new' => $customerData,



			'history' => $historygroup,



			'cabang' => $cabang,



			'biaya' => $biaya



		);



		echo json_encode($data_json);

	}







	//API Tambah Service Pelaporan



	public function service_pelaporan_tambah_post()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$rules = [







			'required' 	=> [







				['kode'], ['cabang'], ['tgl_pelaporan'], ['perusahaan'], ['whatsapp'], ['projects'], ['keluhan'], ['description'] //,['biaya_service'],['biaya_akomodasi']







			]







		];







		$validate 	= Validation::check($rules, 'post');







		if (!$validate->auth) {







			echo goResult(false, $validate->msg);







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 1) == '0') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		if (substr($this->input->post('whatsapp'), 0, 3) == '+62') {







			echo goResult(false, "Nomor whatsapp salah");







			return;

		}















		if ($this->input->post('projects') == 2) {







			$rules2 = [







				'required' 	=> [







					['invoice'], ['total_invoice']







				]







			];















			$validate2 	= Validation::check($rules2, 'post');







			if (!$validate2->auth) {







				echo goResult(false, $validate2->msg);







				return;

			}















			$barang 	= $this->input->post('barang');







			if (count($barang) < 1) {







				echo goResult(false, 'Barang is required');







				return;

			}

		} else {







			$rules2 = [







				'required' 	=> [







					['barang']







				]







			];







			$validate2 	= Validation::check($rules2, 'post');







			if (!$validate2->auth) {







				echo goResult(false, $validate2->msg);







				return;

			}

		}















		if ($this->input->post('tambah_customer') == 1) {







			$rules3 = [







				'required' 	=> [







					['kode_perusahaan'], ['nama_perusahaan'], ['contact_person_perusahaan'], ['email_perusahaan'], ['phone_perusahaan1'], ['phone_perusahaan2'], ['alamat_perusahaan'], ['kota_perusahaan']







				]







			];







			$validate3 	= Validation::check($rules3, 'post');







			if (!$validate3->auth) {







				echo goResult(false, $validate3->msg);







				return;

			}















			$kota 									= KotaModel::find($this->input->post('kota_perusahaan'));















			$newCustomer 							= new CustomernewModel;







			$newCustomer->code						= $this->input->post('kode_perusahaan');







			$newCustomer->name						= ucwords($this->input->post('contact_person_perusahaan'));







			$newCustomer->email						= $this->input->post('email_perusahaan');







			$newCustomer->status_sheets 			= 0;







			$newCustomer->active 					= 1;







			$newCustomer->password 					= DefuseLib::encrypt(12345678);







			$newCustomer->status 					= 'customer';







			$newCustomer->status_regis 				= 'regclient';







			$newCustomer->nama_perusahaan			= ucwords($this->input->post('nama_perusahaan'));







			$newCustomer->alamat_penagihan_pengiriman 		= 'Y';







			$newCustomer->alamat_penagihan 			= $this->input->post('alamat_perusahaan');







			$newCustomer->alamat_pengiriman 		= $this->input->post('alamat_perusahaan');







			$newCustomer->alamat_perusahaan 		= $this->input->post('alamat_perusahaan');







			$newCustomer->id_kota 					= $kota->id;







			$newCustomer->kota_perusahaan 			= $kota->name;







			$newCustomer->country_code 				= 'id';







			$newCustomer->tlp_whatsapp 				= 'N';







			$newCustomer->tlp_perusahaan 			= $this->input->post('phone_perusahaan1');







			$newCustomer->whatsapp 					= $this->input->post('phone_perusahaan2');















			if ($newCustomer->save()) {







				$id_customer 				= $newCustomer->id;

			} else {







				$id_customer 				= $this->input->post('perusahaan');

			}

		} else {







			$rules3 = [







				'required' 	=> [







					['perusahaan']







				]







			];







			$validate3 	= Validation::check($rules3, 'post');







			if (!$validate3->auth) {







				echo goResult(false, $validate3->msg);







				return;

			}















			$id_customer 			= $this->input->post('perusahaan');

		}















		$teknisi  	= TeknisiModel::find($username);







		$customer 	= CustomernewModel::find($this->input->post('perusahaan'));







		$service 	= new ServiceModel;















		foreach ($_FILES['image']['name'] as $file) {







			if (empty($file)) {







				echo goResult(false, "Opss! Gambar Tidak Ada Atau Tidak Sesuai ");







				return;

			}

		}















		if ($this->input->post('projects') == 2) {















			$invoicepenjualan 			= PenjualanModel::find($this->input->post('invoice'));







			if (!$invoicepenjualan) {







				echo goResult(false, 'invoice tidak ada');







				return;

			}







			$invoicepenjualan->status 	= '1';







			$invoicepenjualan->save();















			$service->id_penjualan 		= $invoicepenjualan->id;







			$service->status_invoice 	= 1;















			$service->total_invoice 	= (int) str_replace('.', '', $this->input->post('total_invoice'));







			$total_invoice 				= (int) str_replace('.', '', $this->input->post('total_invoice'));















			if ($total_invoice < 10000000 && count($barang) < 3) {







				$bonus 					= BiayainstallModel::find(1);

			} elseif ((($total_invoice > 10000000 && $total_invoice < 50000000) && count($barang) <= 3) || ($total_invoice < 10000000 && count($barang) >= 3)) {







				$bonus 					= BiayainstallModel::find(2);

			} elseif ($total_invoice > 50000000 && count($barang) < 3) {







				$bonus 					= BiayainstallModel::find(2);

			} elseif ($total_invoice > 50000000 && count($barang) >= 3) {







				$bonus 					= BiayainstallModel::find(3);

			} else {







				$bonus 					= BiayainstallModel::find(1);

			}















			$service->id_bonusinstall 		= $bonus->id;







			$service->nominalbonus_install 	= $bonus->price;

		}















		$service->kode 					= $this->input->post('kode');







		$service->id_cabang 			= $this->input->post('cabang');







		$service->tgl_pelaporan 		= $this->input->post('tgl_pelaporan');







		$service->name 					= $this->input->post('keluhan');







		$service->whatsapp 				= $this->input->post('whatsapp');







		$service->id_customernew 		= $id_customer;







		$service->id_teknisi 			= $teknisi->id;







		$service->id_projects 			= $this->input->post('projects');















		if ($this->input->post('dataphone1') == 1 && $this->input->post('dataphone2') == 0) {







			$service->status_whatsapp 	= 'phone1';

		} elseif ($this->input->post('dataphone1') == 0 && $this->input->post('dataphone2') == 1) {







			$service->status_whatsapp 	= 'phone2';

		} else {







			$service->status_whatsapp 	= 'notsame';

		}















		if ($this->input->post('projects') == 3) {







			$service->id_barang 		= $this->input->post('barang');

		}















		$service->keluhan 				= $this->input->post('description');







		$service->status 				= 0;







		$service->status_agingpending 	= 1;















		if ($this->input->post('biaya_service') == '' && $this->input->post('biaya_akomodasi') == '') {







			$service->biaya 				= 4;







			$service->biaya_akomodasi 		= 0;







			$service->status_bayar 			= 0;







			$biayaService 					= BiayaserviceModel::find(4);







			$service->nominalbiaya_service 	= $biayaService->price;







			$service->status_biaya 			= 1;















			$sumakomodasi 					= 0;







			$sumservice 					= $biayaService->price;







			$countbiaya 					= $biayaService->price;

		} elseif ($this->input->post('biaya_service') != '' && $this->input->post('biaya_akomodasi') == '') {







			$service->biaya 				= $this->input->post('biaya_service');







			if ($this->input->post('biaya_service') == 1) {







				$biayaService 				= BiayaserviceModel::find(1);

			} elseif ($this->input->post('biaya_service') == 2) {







				$biayaService 				= BiayaserviceModel::find(2);

			} elseif ($this->input->post('biaya_service') == 3) {







				$biayaService 				= BiayaserviceModel::find(3);

			} else {







				$biayaService 				= BiayaserviceModel::find(4);

			}















			$service->nominalbiaya_service 	= $biayaService->price;







			$service->biaya_akomodasi 		= 0;







			$service->status_bayar 			= 0;







			$service->status_biaya 			= 1;















			$sumakomodasi 					= 0;







			$sumservice 					= $biayaService->price;







			$countbiaya 					= $biayaService->price;

		} elseif ($this->input->post('biaya_service') == '' && $this->input->post('biaya_akomodasi') != '') {







			$service->biaya 				= 4;







			$service->biaya_akomodasi 		= (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));















			if ($this->input->post('biaya_service') == 4 && $this->input->post('biaya_akomodasi') == 0) {







				$service->status_bayar = 1;

			} else {







				$service->status_bayar = 0;

			}















			$biayaService 					= BiayaserviceModel::find(4);







			$service->nominalbiaya_service 	= $biayaService->price;







			$service->status_biaya 			= 1;















			$sumakomodasi 					= (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));







			$sumservice 					= $biayaService->price;







			$countbiaya 					= $biayaService->price + (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));

		} else {







			$service->biaya 				= $this->input->post('biaya_service');







			$service->biaya_akomodasi 		= (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));















			$biaya 							= $this->input->post('biaya_service');







			$biaya_akomodasi 				= (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));















			if ($biaya == 4 && $biaya_akomodasi == 0) {







				$service->status_bayar = 1;

			} else {







				$service->status_bayar = 0;

			}















			if ($biaya == 1) {







				$biayaService 				= BiayaserviceModel::find(1);

			} elseif ($biaya == 2) {







				$biayaService 				= BiayaserviceModel::find(2);

			} elseif ($biaya == 3) {







				$biayaService 				= BiayaserviceModel::find(3);

			} else {







				$biayaService 				= BiayaserviceModel::find(4);

			}















			$service->nominalbiaya_service 	= $biayaService->price;







			$service->status_biaya 			= 1;















			$sumakomodasi 					= (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));







			$sumservice 					= $biayaService->price;







			$countbiaya 					= $biayaService->price + (int) str_replace('.', '', $this->input->post('biaya_akomodasi'));

		}















		if ($countbiaya > 0) {







			$id_customerbee 		= '';







			$customerbee 			= CustomernewModel::find($id_customer);







			$mitraBisnisUD 			= ApiBee::getMitrabisnisUD();















			foreach ($mitraBisnisUD['data'] as $key => $value) {







				if ($value->code == $customerbee->code) {







					$id_customerbee = $id_customerbee . '' . $value->id;

				}

			}















			if ($service->id_cabang == '1') {







				$branch 		= "1";







				$gudang 		= "1";

			} else {







				$branch 		= "2";







				$gudang 		= "5";

			}















			$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'UD')->desc()->first();







			if (!$codeorderpenjualan) {







				$isToday 			= explode('-', date('Y-m-d'));







				$isYear 			= $isToday[0];







				$year 				= substr($isYear, -2);







				$month 				= $isToday[1];







				$day 				= $isToday[2];







				$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

			} else {















				$today 				= explode(' ', $codeorderpenjualan->created_at);







				$dateToday 			= substr($today[0], 0, -3);















				if ($dateToday == date('Y-m')) {















					$year 			= substr(date('Y'), -2);







					$codepenawaran 	= explode('-', $codeorderpenjualan->no_transaksi);







					$sumpenawaran 	= (int) $codepenawaran[2];















					$newcode 			= $sumpenawaran + 1;







					if ($newcode > 0 && $newcode <= 9) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-00' . $newcode;

					} elseif ($newcode > 9 && $newcode <= 99) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-0' . $newcode;

					} else {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-' . $newcode;

					}

				} else {







					$isToday 			= explode('-', date('Y-m-d'));







					$isYear 			= $isToday[0];







					$year 				= substr($isYear, -2);







					$month 				= $isToday[1];







					$day 				= $isToday[2];







					$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

				}

			}















			$totalprice 		= $countbiaya;







			$product            = array();







			$product[]          = array(







				"baseprice"             => $sumakomodasi,







				"basesubtotal"          => $sumakomodasi * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 1,







				"item_id"               => "1645",







				"itemname"              => "Biaya Akomodasi",







				"listprice"             => $sumakomodasi,







				"qty"                   => 1,







				"subtotal"              => $sumakomodasi * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => '4742',







				"wh_id"                 => $gudang







			);















			$product[]          = array(







				"baseprice"             => $sumservice,







				"basesubtotal"          => $sumservice * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 2,







				"item_id"               => "1644",







				"itemname"              => "Biaya Service",







				"listprice"             => $sumservice,







				"qty"                   => 1,







				"subtotal"              => $sumservice * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => "4739",







				"wh_id"                 => $gudang







			);















			$mainproduct        = array();







			$mainproduct[]      = array(







				"branch_id" => $branch,







				"bp_id"     => $id_customerbee,







				"crc_id"    => "1",







				"discexp"   => "0",







				"sods"      => $product,







				"srep_id"   => "7",







				"taxed"     => "0",







				"subtotal"  => $totalprice,







				"total"     => $totalprice,







				"trxdate"   => date('Y-m-d'),







				"trxno"     => $newcodeorder







			);















			$dataInsert         = array(







				"soarray"       => $mainproduct







			);















			$newpenawaran 		= ApiBee::postPenawaranUD($dataInsert);







			if ($newpenawaran['status'] != 200) {







				echo goResult(false, $newpenawaran['msg']);







				return;

			}

		}















		if ($service->save()) {















			if ($countbiaya > 0) {







				$penawaranbee 					= new PenawaranbeeModel;







				$penawaranbee->tgl_transaksi 	= date('Y-m-d');







				$penawaranbee->name_db 			= 'UD';







				$penawaranbee->no_transaksi 	= $newcodeorder;







				$penawaranbee->id_sales 		= 7;







				$penawaranbee->id_teknisi 		= 43;







				$penawaranbee->id_cabang 		= $branch;







				$penawaranbee->id_service 		= $service->id;







				$penawaranbee->id_mitrabisnis 	= $id_customerbee;







				$penawaranbee->code_mitrabisnis = $customerbee->code;







				$penawaranbee->subtotal 		= $totalprice;















				if ($penawaranbee->save()) {







					$penawaranbeedetail 					= new PenawaranbeedetailModel;







					$penawaranbeedetail->id_penawaranbee 	= $penawaranbee->id;







					$penawaranbeedetail->id_gudang 			= $gudang;







					$penawaranbeedetail->id_item 			= '1645';







					$penawaranbeedetail->code_item 			= '001082';







					$penawaranbeedetail->name_item 			= 'Biaya Akomodasi';







					$penawaranbeedetail->price 				= $service->biaya_akomodasi;







					$penawaranbeedetail->qty 				= 1;







					$penawaranbeedetail->subtotal 			= $service->biaya_akomodasi;







					$penawaranbeedetail->ppn 				= 0;







					$penawaranbeedetail->save();















					$penawaranbeedetail2 					= new PenawaranbeedetailModel;







					$penawaranbeedetail2->id_penawaranbee 	= $penawaranbee->id;







					$penawaranbeedetail2->id_gudang 		= $gudang;







					$penawaranbeedetail2->id_item 			= '1644';







					$penawaranbeedetail2->code_item 		= '001081';







					$penawaranbeedetail2->name_item 		= 'Biaya Service';







					$penawaranbeedetail2->price 			= $service->nominalbiaya_service;







					$penawaranbeedetail2->qty 				= 1;







					$penawaranbeedetail2->subtotal 			= $service->nominalbiaya_service;







					$penawaranbeedetail2->ppn 				= 0;







					$penawaranbeedetail2->save();

				}

			}















			if ($service->id_projects == 2) {







				$codeprojects 	= 'I' . $service->kode;

			} else {







				$codeprojects 	= 'S' . $service->kode;

			}















			$filename 	= 'SERVICE__' . $codeprojects . '__' . date('Ymdhis');















			$upload 	= $this->upload_files(__DIR__ . '/../../public_html/images/service', $_FILES['image'], $filename);







			if ($upload['auth']	== false) {







				foreach ($upload['msg'] as $key => $value) {







					remFile(__DIR__ . '/../../public_html/images/service/' . $value['file_name']);

				}







				echo goResult(false, 'Opss! Gambar Tidak Terupload');







				return;

			}















			foreach ($upload['msg'] as $key => $value) {







				$image 					= new ServiceimageModel;







				$image->id_service 		= $service->id;







				$image->kategori 		= 'service';







				$image->image 			= $value['file_name'];







				$image->save();

			}















			$image 						= ServiceimageModel::where('id_service', $service->id)->first();















			if ($image) {







				$service->image 		= $image->image;







				$service->save();

			}















			if ($service->id_projects == 2) {







				for ($i = 0; $i < count($barang); $i++) {







					$barangInstall 				= new BaranginstallModel;







					$barangInstall->id_service 	= $service->id;







					$barangInstall->id_barang 	= $barang[$i];







					$barangInstall->save();

				}















				$barangFirst 				= BaranginstallModel::where('id_service', $service->id)->first();







				if ($barangFirst) {







					$service->id_barang 	= $barangFirst->id_barang;







					$service->save();

				}















				for ($i = 0; $i < count($barang); $i++) {















					$barangCode 			= BarangModel::find($barang[$i]);







					$barcodeOld 			= BarangBarcodeModel::where('id_barang', $barangCode->id)->desc()->first();















					if (!$barcodeOld) {







						if ($i > 0 && $i < 10) {







							$countcode 			= '00' . $i;

						} elseif ($i >= 10 && $i < 100) {







							$countcode 			= '0' . $i;

						} else {







							$countcode 			= $i;

						}

					} else {







						$countcodeold 			= explode('-', $barcodeOld->code);







						$countcodevalue 		= (int) $countcodeold[2];







						$counter 				= $countcodevalue + $i;















						if ($counter > 0 && $counter < 10) {







							$countcode 			= '00' . $counter;

						} elseif ($counter >= 10 && $counter < 100) {







							$countcode 			= '0' . $counter;

						} else {







							$countcode 			= $counter;

						}

					}















					$penjualanid 					= PenjualanModel::find($service->id_penjualan);















					$barcodeprint 					= new BarangBarcodeModel;







					$barcodeprint->id_barang 		= $barangCode->id;







					$barcodeprint->id_customernew 	= $id_customer;







					$barcodeprint->id_teknisi 		= $this->data['teknisi']->id;







					$barcodeprint->code_penjualan 	= $penjualanid->no_penjualan;







					$barcodeprint->code 			= $barangCode->new_kodesku . '-' . date('d') . date('m') . substr(date('Y'), -2) . '-' . $countcode;







					$barcodeprint->image 			= $barangCode->new_kodesku . '-' . date('d') . date('m') . substr(date('Y'), -2) . '-' . $countcode . '.png';







					$barcodeprint->tgl_print 		= date('Y-m-d');







					if ($barcodeprint->save()) {







						$qrcode['cacheable']    = true; //boolean, the default is true







						$qrcode['cachedir']     = ''; //string, the default is application/cache/







						$qrcode['errorlog']     = ''; //string, the default is application/logs/







						$qrcode['imagedir']     = 'images/barangbarcode/'; //direktori penyimpanan qr code







						$qrcode['quality']      = true; //boolean, the default is true







						$qrcode['size']         = '1024'; //interger, the default is 1024







						$qrcode['black']        = array(224, 255, 255); // array, default is array(255,255,255)







						$qrcode['white']        = array(70, 130, 180); // array, default is array(0,0,0)







						$this->ciqrcode->initialize($qrcode);















						$image_name				= $barcodeprint->code . '.png'; //buat name dari qr code sesuai dengan nim















						$params['data'] 		= $barcodeprint->code; //data yang akan di jadikan QR CODE







						$params['level'] 		= 'H'; //H=High







						$params['size'] 		= 10;







						$params['savename'] 	= FCPATH . $qrcode['imagedir'] . $image_name; //simpan image QR CODE ke folder assets/images/







						$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE







					}

				}

			}















			if ($this->input->post('tambah_customer') == 1) {







				$data['newCust'] 		= 'y';







				$newCust 				= CustomernewModel::find($id_customer);







				$kotaCust 				= KotaModel::find($newCust->id_kota);







				$data['code'] 			= $newCust->code;







				$data['name'] 			= $newCust->nama_perusahaan;







				$data['ownname'] 		= $newCust->name;







				$data['address'] 		= $newCust->alamat_penagihan;







				$data['businessname'] 	= $newCust->nama_perusahaan;







				$data['city_code'] 		= $kotaCust->code;







				$data['namecont'] 		= $newCust->name;







				$data['hp'] 			= '0' . $newCust->tlp_perusahaan;







				$data['email'] 			= $newCust->email;

			} else {







				$data['newCust'] 		= 'n';

			}















			if ($service->id_projects == 2) {







				$texthistory 			= 'melakukan permintaan install';







				$customerhistory 		= 'anda telah melaporkan permintaan install';

			} else {







				$texthistory 			= 'melakukan permintaan service';







				$customerhistory 		= 'anda telah melaporkan permintaan service';

			}















			$servicehistory 			= new ServicehistoryModel;







			$servicehistory->id_service = $service->id;







			$servicehistory->no_spk 	= $codeprojects;







			$servicehistory->name 		= $texthistory;







			$servicehistory->customer 	= $customerhistory;







			$servicehistory->time 		= date('Y-m-d H:i:s');







			$servicehistory->save();















			if ($service->status == '0') {







				$namestatus 	= 'Pelaporan';

			} elseif ($service->status == '1') {







				$namestatus 	= 'Pengerjaan';

			} elseif ($service->status == '2') {







				$namestatus 	= 'Menunggu Konfirmasi';

			} elseif ($service->status == '3') {







				$namestatus 	= 'Selesai';

			} else {







				$namestatus 	= 'Pending';

			}















			//wa cust







			$numberwacust 		= '62' . $service->whatsapp;







			$template_msg 		= '84d4233e-0676-435f-997c-df3bfafe6048'; //add_pelaporan_01







			$language 			= array(







				"code" 			=> 'id'







			);















			$body_message 		= array();







			$body_message[] 	= array(







				"key" 			=> '1',







				"value" 		=> 'code',







				"value_text" 	=> $codeprojects







			);















			$body_message[] 	= array(







				"key" 			=> '2',







				"value" 		=> 'price_service',







				"value_text" 	=> 'Rp. ' . number_format($service->nominalbiaya_service)







			);















			$body_message[] 	= array(







				"key" 			=> '3',







				"value" 		=> 'price_akomodasi',







				"value_text" 	=> 'Rp. ' . number_format($service->biaya_akomodasi)







			);















			$body_message[] 	= array(







				"key" 			=> '4',







				"value" 		=> 'status',







				"value_text" 	=> $namestatus







			);















			$body_message[] 	= array(







				"key" 			=> '5',







				"value" 		=> 'link',







				"value_text" 	=> 'https://maxipro.id/tracking'







			);















			$parameters 		= array(







				"body" 			=> $body_message







			);















			$dataInsert 		= array(







				"to_number" 				=> $numberwacust,







				"to_name" 					=> $service->customernew->name,







				"message_template_id" 		=> $template_msg,







				"channel_integration_id" 	=> $this->mxp_channel_id,







				"language" 					=> $language,







				"parameters" 				=> $parameters,







				"execute_type" 				=> 'immediately',







				"send_at" 					=> date('Y-m-d H:i:s')







			);















			$broadcastmessage 	= ApiQontak::broadcastMessage($dataInsert);















			//pancake







			/*$messagepancake 	= '➡️ *PELAPORAN*















			*PERMINTAAN* anda telah kami terima dan telah masuk ke dalam sistem kami.















			🔰 ID Pelaporan : *'.$codeprojects.'*







			🔰 Biaya Service : *Rp. '.number_format($service->nominalbiaya_service).'*







			🔰 Biaya Akomodasi : *Rp. '.number_format($service->biaya_akomodasi).'*















			⭐ *STATUS : '.$namestatus.'* ⭐















			Harap menyimpan ID Pelaporan ini untuk memudahkan pelacakan.







			Lacak Pelaporan Anda di :







			https://maxipro.id/tracking















			*Ketentuan :*







			```✅ Biaya Service dihitung untuk per masalah hingga selesai.







			✅ Biaya Akomodasi dihitung untuk per kunjungan.







			✅ Biaya Akomodasi meliputi transportasi pulang pergi teknisi, tidak meliputi penginapan (bila menginap di luar kota).







			✅ Bila ada pergantian Sparepart, akan ditagihkan secara terpisah.```















			*🙏🤝Terima Kasih 🤝🙏*';















			$finalnumber 		= '62'.$service->whatsapp;







			$page_id 			= 'wa_c.us@'.$this->pancake_numberForm;







			$conversations_id 	= 'wa_'.$this->pancake_numberForm.'@c.us_'.$finalnumber.'@c.us';







			$urlMessage 		= 'https://pages.fm/api/v1/pages/'.$page_id.'/conversations/'.$conversations_id.'/messages?access_token='.$this->pancake_accessToken;















			$dataMessage 		= array(







				"action" 		=> 'reply_inbox',







				"message" 		=> $messagepancake,







				"thread_key" 	=> ''







			);















			$curlpancake = curl_init();







			curl_setopt_array($curlpancake, array(







				  CURLOPT_URL 			=> $urlMessage,







				  CURLOPT_RETURNTRANSFER 	=> true,







				  CURLOPT_ENCODING 		=> "",







				  CURLOPT_MAXREDIRS 		=> 10,







				  CURLOPT_TIMEOUT 		=> 0,







				  CURLOPT_FOLLOWLOCATION 	=> true,







				  CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_1_1,







				  CURLOPT_CUSTOMREQUEST 	=> "POST",







				  CURLOPT_POSTFIELDS 		=> $dataMessage,







				  CURLOPT_HTTPHEADER 		=> array(







					"page_id: ".$page_id,







					"conversation_id: ".$conversations_id







				  ),







			));















			$responsepancake = curl_exec($curlpancake);







			curl_close($curlpancake);*/















			$multiple 					= new ServicemultipleModel;







			$multiple->id_service 		= $service->id;







			$multiple->id_biaya 		= $service->biaya;







			$multiple->biaya_service 	= $service->nominalbiaya_service;







			$multiple->biaya_akomodasi 	= $service->biaya_akomodasi;







			$multiple->save();















			$data['username'] 			= $teknisi->username;







			$data['auth'] 				= true;







			$data['msg'] 				= 'Service berhasil ditambah';







			echo json_encode($data);







			return;

		} else {







			echo goResult(false, 'Service gagal ditambah');







			return;

		}

	}







	//API GET To View Tambah Service Pelaporan



	public function service_pelaporan_view_tambah_get()



	{



		// $kota 			= KotaModel::orderBy('name', 'asc')->get();



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$customer_vip 			= CustomervipModel::where('id_customernew', '!=', NULL)->asc()->get();



		$countVIP 		= count($customer_vip);



		$customer = array();



		$customervip = array();



		$customernonvip = array();



		$kode = array();



		$barang = array();



		if (count($customer_vip) <= 0) {











			$customer[] = CustomernewModel::where('status', 'customer')



				->where('status_deleted', 0)



				->orderBy('nama_perusahaan', 'asc')



				->select('id', 'code', 'nama_perusahaan')



				->get();

		} else {



			$IdCustomer 			= array();







			foreach ($customer_vip as $key => $result) {







				$IdCustomer[] 		= $result->id_customer;

			}







			$customervip[] = CustomernewModel::where('status', 'customer')



				->where('status_deleted', 0)



				->whereIn('id', $IdCustomer)



				->orderBy('nama_perusahaan', 'asc')



				->select('id', 'code', 'nama_perusahaan')



				->get();







			$customernonvip[] = CustomernewModel::where('status', 'customer')



				->where('status_deleted', 0)



				->whereNotIn('id', $IdCustomer)



				->orderBy('nama_perusahaan', 'asc')



				->select('id', 'code', 'nama_perusahaan')



				->get();

		}



		$customerOld 					= CustomernewModel::desc()->first();







		if (!$customerOld) {







			$isToday 					= explode('-', date('Y-m-d'));







			$isYear 					= $isToday[0];







			$year 						= substr($isYear, -2);







			$month 						= $isToday[1];







			$day 						= $isToday[2];







			$newcode 					= 'C' . $year . '' . $month . '' . $day . '01';

		} else {







			$today 						= explode(' ', $customerOld->created_at);







			if ($today[0] == date('Y-m-d')) {







				$alphabet 				= (int) str_replace('C', '', $customerOld->code);







				$code 					= $alphabet + 1;







				$newcode 				= 'C' . $code;

			} else {







				$isToday 				= explode('-', date('Y-m-d'));







				$isYear 				= $isToday[0];







				$year 					= substr($isYear, -2);







				$month 					= $isToday[1];







				$day 					= $isToday[2];







				$newcode 				= 'C' . $year . '' . $month . '' . $day . '01';

			}

		}







		$barang[] 		= BarangModel::where('status_deleted', '0') // Urutan descending berdasarkan created_at (atau kolom lain jika diinginkan)



			->orderBy('created_at', 'desc') // Urutan descending berdasarkan created_at (atau kolom lain jika diinginkan)



			->select('id', 'new_kode')->get();







		$query = $this->db->select('name,id,new_kode')



			->from('barang')



			->where('status_deleted', '0')



			->order_by('created_at', 'desc')



			->get();







		$name_barang = $query->result_array();







		$query_projects 		= ProjectsModel::where('id', '!=', 1)->asc()->get();







		$kode_perusahaan 	= $newcode;







		$service 				= ServiceModel::orderBy('kode', 'desc')->first();



		$biaya 			= BiayaserviceModel::asc()->get();



		$kode = '';



		if (!$service) {







			$isToday 			= explode('-', date('Y-m-d'));







			$isYear 			= $isToday[0];







			$year 				= substr($isYear, -2);







			$month 				= $isToday[1];







			$day 				= $isToday[2];







			$kode 		= $year . '' . $month . '001';

		} else {







			$today 		= explode(' ', $service->created_at);







			$dateToday 	= substr($today[0], 0, -3);











			if ($dateToday == date('Y-m')) {















				$kode 	= $service->kode + 1;

			} else {







				$isToday 		= explode('-', date('Y-m-d'));







				$isYear 		= $isToday[0];







				$year 			= substr($isYear, -2);







				$month 			= $isToday[1];







				$day 			= $isToday[2];







				$kode 	= $year . '' . $month . '001';

			}

		}



		$biaya 			= BiayaserviceModel::asc()->get();



		$cabang 		= CabangModel::where('status', '1')->asc()->get();



		$data = array(



			'kode' => $kode,



			'data' => $customer,



			'customervip' => $customervip,



			'customernonvip' => $customernonvip,



			'biaya' => $biaya,



			'barang' => $name_barang,



			'projects' => $query_projects,



			'cabang' => $cabang,



		);



		echo json_encode($data);

	}







	//API Delete Service Pelaporan



	public function service_pelaporan_hapus_delete()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$service 				= ServiceModel::find($id);















		if (!$service) {







			echo goResult(false, 'Maaf, service tidak ada');







			return;

		}















		$service->status 		= 5;







		$service->status_siap 	= '0';







		$service->save();















		if ($service->id_projects == 2) {







			$nomorspk 			= 'I' . $service->kode;

		} else {







			$nomorspk 			= 'S' . $service->kode;

		}















		$servicehistory 			= new ServicehistoryModel;







		$servicehistory->id_service = $service->id;







		$servicehistory->no_spk 	= $nomorspk;







		$servicehistory->name 		= "Delete laporan";







		$servicehistory->time 		= date('Y-m-d H:i:s');







		$servicehistory->save();















		$numberwacust 		= '62' . $service->whatsapp;







		$template_msg 		= '1c08bd77-e45e-459c-a69a-7dadff88a751'; //cancel_permintaan







		$language 			= array(







			"code" 			=> 'id'







		);















		$body_message 		= array();







		$body_message[] 	= array(







			"key" 			=> '1',







			"value" 		=> 'spk_number',







			"value_text" 	=> $nomorspk







		);















		$parameters 		= array(







			"body" 			=> $body_message







		);















		$dataInsert 		= array(







			"to_number" 				=> $numberwacust,







			"to_name" 					=> $service->customernew->name,







			"message_template_id" 		=> $template_msg,







			"channel_integration_id" 	=> $this->mxp_channel_id,







			"language" 					=> $language,







			"parameters" 				=> $parameters,







			"execute_type" 				=> 'immediately',







			"send_at" 					=> date('Y-m-d H:i:s')















		);















		$broadcastmessage 	= ApiQontak::broadcastMessage($dataInsert);



		if ($broadcastmessage) {







			echo goResult(true, 'Data anda berhasil dihapus');



			return;

		}



		echo goResult(false, 'Data anda gagal dihapus');

	}







	//API Sync Service Pelaporan



	public function service_pelaporan_sync_get()



	{



		$teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$id = $this->input->get('id');



		$service 				= ServiceModel::find($id);







		$orderpenjualan 		= PenawaranbeeModel::where('id_service', $service->id)->desc()->first();







		if (!$service) {







			echo goResult(false, 'Service not found');







			return;

		}















		if ($orderpenjualan) {







			echo goResult(false, 'Invoice is already');







			return;

		}















		$countbiaya 			= $service->nominalbiaya_service + $service->biaya_akomodasi;







		if ($countbiaya > 0) {







			$id_customerbee 	= '';







			$customerbee 		= CustomernewModel::find($service->id_customernew);







			$mitraBisnisUD 		= ApiBee::getMitrabisnisUD();















			foreach ($mitraBisnisUD['data'] as $key => $value) {







				if ($value->code == $customerbee->code) {







					$id_customerbee = $id_customerbee . '' . $value->id;

				}

			}















			if ($service->id_cabang == 1) {







				$branch 		= "1";







				$gudang 		= "1";

			} else {







				$branch 		= "2";







				$gudang 		= "5";

			}















			$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'UD')->desc()->first();







			if (!$codeorderpenjualan) {







				$isToday 			= explode('-', date('Y-m-d'));







				$isYear 			= $isToday[0];







				$year 				= substr($isYear, -2);







				$month 				= $isToday[1];







				$day 				= $isToday[2];







				$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

			} else {















				$today 				= explode(' ', $codeorderpenjualan->created_at);







				$dateToday 			= substr($today[0], 0, -3);















				if ($dateToday == date('Y-m')) {















					$year 			= substr(date('Y'), -2);







					$codepenawaran 	= explode('-', $codeorderpenjualan->no_transaksi);







					$sumpenawaran 	= (int) $codepenawaran[2];















					$newcode 			= $sumpenawaran + 1;







					if ($newcode > 0 && $newcode <= 9) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-00' . $newcode;

					} elseif ($newcode > 9 && $newcode <= 99) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-0' . $newcode;

					} else {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-' . $newcode;

					}

				} else {







					$isToday 			= explode('-', date('Y-m-d'));







					$isYear 			= $isToday[0];







					$year 				= substr($isYear, -2);







					$month 				= $isToday[1];







					$day 				= $isToday[2];







					$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

				}

			}















			$sumakomodasi 		= $service->biaya_akomodasi;







			$sumservice 		= $service->nominalbiaya_service;







			$totalprice 		= $countbiaya;







			$product            = array();







			$product[]          = array(







				"baseprice"             => $sumakomodasi,







				"basesubtotal"          => $sumakomodasi * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 1,







				"item_id"               => "1645",







				"itemname"              => "Biaya Akomodasi",







				"listprice"             => $sumakomodasi,







				"qty"                   => 1,







				"subtotal"              => $sumakomodasi * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => '4742',







				"wh_id"                 => $gudang







			);















			$product[]          = array(







				"baseprice"             => $sumservice,







				"basesubtotal"          => $sumservice * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 2,







				"item_id"               => "1644",







				"itemname"              => "Biaya Service",







				"listprice"             => $sumservice,







				"qty"                   => 1,







				"subtotal"              => $sumservice * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => "4739",







				"wh_id"                 => $gudang







			);















			$mainproduct        = array();







			$mainproduct[]      = array(







				"branch_id" => $branch,







				"bp_id"     => $id_customerbee,







				"crc_id"    => "1",







				"discexp"   => "0",







				"sods"      => $product,







				"srep_id"   => "7",







				"taxed"     => "0",







				"subtotal"  => $totalprice,







				"total"     => $totalprice,







				"trxdate"   => date('Y-m-d'),







				"trxno"     => $newcodeorder







			);















			$dataInsert         = array(







				"soarray"       => $mainproduct







			);















			$newpenawaran 		= ApiBee::postPenawaranUD($dataInsert);







			if ($newpenawaran['status'] != 200) {







				echo goResult(false, $newpenawaran['msg']);







				return;

			}















			$penawaranbee 					= new PenawaranbeeModel;







			$penawaranbee->tgl_transaksi 	= date('Y-m-d');







			$penawaranbee->name_db 			= 'UD';







			$penawaranbee->no_transaksi 	= $newcodeorder;







			$penawaranbee->id_sales 		= 7;







			$penawaranbee->id_teknisi 		= 43;







			$penawaranbee->id_cabang 		= $branch;







			$penawaranbee->id_service 		= $service->id;







			$penawaranbee->id_mitrabisnis 	= $id_customerbee;







			$penawaranbee->code_mitrabisnis = $customerbee->code;







			$penawaranbee->subtotal 		= $totalprice;















			if ($penawaranbee->save()) {







				$penawaranbeedetail 					= new PenawaranbeedetailModel;







				$penawaranbeedetail->id_penawaranbee 	= $penawaranbee->id;







				$penawaranbeedetail->id_gudang 			= $gudang;







				$penawaranbeedetail->id_item 			= '1645';







				$penawaranbeedetail->code_item 			= '001082';







				$penawaranbeedetail->name_item 			= 'Biaya Akomodasi';







				$penawaranbeedetail->price 				= $service->biaya_akomodasi;







				$penawaranbeedetail->qty 				= 1;







				$penawaranbeedetail->subtotal 			= $service->biaya_akomodasi;







				$penawaranbeedetail->ppn 				= 0;







				$penawaranbeedetail->save();















				$penawaranbeedetail2 					= new PenawaranbeedetailModel;







				$penawaranbeedetail2->id_penawaranbee 	= $penawaranbee->id;







				$penawaranbeedetail2->id_gudang 		= $gudang;







				$penawaranbeedetail2->id_item 			= '1644';







				$penawaranbeedetail2->code_item 		= '001081';







				$penawaranbeedetail2->name_item 		= 'Biaya Service';







				$penawaranbeedetail2->price 			= $service->nominalbiaya_service;







				$penawaranbeedetail2->qty 				= 1;







				$penawaranbeedetail2->subtotal 			= $service->nominalbiaya_service;







				$penawaranbeedetail2->ppn 				= 0;







				$penawaranbeedetail2->save();















				echo goResult(true, 'Sync invoice success');







				return;

			} else {







				echo goResult(true, 'Sync invoice success');







				return;

			}

		} else {







			echo goResult(false, 'Price cannot zero');







			return;

		}

	}











	//API Get Pengerjaan Service



	public function service_pengerjaan_get()



	{



		$teknisi 			= TeknisiModel::find($this->session->userdata('teknisi_id'));







		// $teknisi            = TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$this->data['teknisi'] = $teknisi;







		$StatusTeknisi 			= $this->data['teknisi']->status;







		$IdTeknisi 				= $this->data['teknisi']->id;







		$tgl_awal 				= $this->input->get('tgl_awal');







		$tgl_akhir 				= $this->input->get('tgl_akhir');







		$kodeFilter 			= $this->input->get('kode');







		$projectFilter 			= $this->input->get('project');







		$companyFilter 			= $this->input->get('company');







		$checkdatevalue 		= $this->input->get('checkdatevalue');











		if (!$checkdatevalue) {







			$checkdatefix 	= 'checked';

		} else {







			$checkdatefix 	= $checkdatevalue;

		}











		if (!$tgl_akhir) {







			$lastDate 		= date('Y-m-d');

		} else {







			if ($tgl_akhir == '') {







				$lastDate 	= date('Y-m-d');

			} else {







				$lastDate 	= $tgl_akhir;

			}

		}







		if (!$tgl_awal) {







			$startDate 		= date('Y-m-d', strtotime($lastDate . '-7 days'));

		} else {







			if ($tgl_awal == '') {







				$startDate 	= date('Y-m-d', strtotime($lastDate . '-7 days'));

			} else {







				$startDate 	= $tgl_awal;

			}

		}











		if (!$projectFilter) {







			$valueproject 	= 'all';

		} else {







			$valueproject 	= $projectFilter;

		}















		if (!$kodeFilter) {







			$valuekode 			= '';







			$valuekodereturn 	= '';

		} else {







			$valuekode 			= substr($kodeFilter, 1);







			$valuekodereturn 	= $kodeFilter;

		}















		if (!$companyFilter) {







			$valuecompany 	= 'all';

		} else {







			$valuecompany 	= $companyFilter;

		}







		if ($checkdatefix == 'checked') {















			if ($valueproject != 'all') {







				if ($valueproject == 'install') {







					$projectservice = 2;

				} else {







					$projectservice = 3;

				}

			}















			if ($valueproject != 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} elseif ($valueproject != 'all' && $valuecompany == 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_projects', $projectservice)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_projects', $projectservice)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} elseif ($valueproject == 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_customernew', $valuecompany)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} else {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->whereDate('tgl_pengerjaan', '>=', $startDate)->whereDate('tgl_pengerjaan', '<=', $lastDate)->where('status', 1)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			}

		} else {















			if ($valueproject != 'all') {







				if ($valueproject == 'install') {







					$projectservice = 2;

				} else {







					$projectservice = 3;

				}

			}















			if ($valueproject != 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_projects', $projectservice)->where('id_customernew', $valuecompany)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} elseif ($valueproject != 'all' && $valuecompany == 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_projects', $projectservice)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_projects', $projectservice)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} elseif ($valueproject == 'all' && $valuecompany != 'all') {















				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_customernew', $valuecompany)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_customernew', $valuecompany)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			} else {











				if ($StatusTeknisi == 'teknisi') {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->where('id_teknisi', $IdTeknisi)->orderBy('tgl_pengerjaan', 'desc')->get();

				} else {







					$service 		= ServiceModel::where('kode', 'LIKE', '%' . $valuekode . '%')->where('status', 1)->orderBy('tgl_pengerjaan', 'desc')->get();

				}

			}

		}











		$page 						= $this->uri->segment(5);







		if (!is_numeric($page)) {







			$page 					= $this->input->get('page');

		}











		$idService 					= array();







		foreach ($service as $value) {







			$idService[] 			= $value->id;

		}







		$paginate					= new Myweb_pagination;















		$total						= count($idService);







		$service 			= ServiceModel::whereIn('id', $idService)->desc()->get();







		$numberpage 		= $page * 20;







		$pagination 		= $paginate->paginate(base_url('teknisi/service_pengerjaan/' . $this->data['teknisi']->username . '/page/'), 6, 20, $total, $page);















		$tgl_awal 			= $startDate;







		$tgl_akhir 			= $lastDate;







		$checkdatevalue 	= $checkdatefix;







		$kodeFilter 		= $valuekodereturn;







		$projectFilter 		= $valueproject;







		$companyFilter 		= $valuecompany;















		$teknisi_tugas 		= SpkModel::where('status_sementara', 1)->desc()->get();







		$all_teknisi 		= TeknisiModel::where('status_regis', 1)->where('status', 'teknisi')->desc()->get();















		$spkPrintId 				= array();







		foreach ($service as $value) {











			$spk_print 				= SpkprintModel::where('id_service', $value->id)->desc()->first();



			if ($spk_print) {







				$spk_printId 		= SpkprintModel::find($spk_print->id);







				$spkPrintId[] 		= $spk_printId->id;

			}

		}











		$spkPrint 			= SpkprintModel::whereIn('id', $spkPrintId)->get();







		$teknisiGagal 		= SpkModel::selectRaw('count(*) as total, status, status_sementara, id_service')->where('status', 0)->where('status_sementara', 0)->groupBy('id_service')->get();







		$historyService 	= SpkprintModel::selectRaw('count(*) as total, id_service')->groupBy('id_service')->get();



		$alasan 		= AlasanModel::asc()->get();



		$barang = array();



		$kode = array();



		$keluhan = array();



		$perusahaan = array();



		$teknisiTugas = array();



		$teknisiTugasId = '';



		$teknisiTugasNama = '';



		$tgl_pengerjaan = array();



		$jenis_projects = array();



		$penjualan = array();







		$alasancustomer = AlasancustomerModel::asc()->get();







		$alasankondisi1 = AlasancustomerModel::where('kategori', 'kondisi1')->where('status', '1')->orderBy('name', 'asc')->get();







		$alasankondisi2 = AlasancustomerModel::where('kategori', 'kondisi2')->where('status', '1')->orderBy('name', 'asc')->get();



		$query = $this->db->select('name,id,new_kode')



			->from('barang')



			->where('status_deleted', '0')



			->where('id_category', '3')



			->order_by('name', 'asc')



			->get();







		$sparepart = $query->result_array();











		foreach ($service as $key => $result) {



			if ($result->id_projects == 2) {







				$jenis_projects[] = $result->projects->name;



				foreach ($result->baranginstall as $resultBarang) {



					$barang[] = $resultBarang->barang->name;



					$kode[] = 'I' . $result->kode;

				}

			} elseif ($result->id_projects == 3) {



				$jenis_projects[] = $result->projects->name;



				$barang[] = $result->barang->name;



				$kode[] = 'S' . $result->kode;

			}



			$penjualan[] = $result->penjualan ? $result->penjualan->no_penjualan : '';



			$keluhan[] = $result->name;



			$perusahaan[] = $result->customernew->nama_perusahaan;



			$tgl_pengerjaan[] = tgl_indo3($result->tgl_pengerjaan);



			foreach ($teknisi_tugas as $tektugservice) {



				if ($result->id == $tektugservice->id_service) {



					$teknisiTugasId = $tektugservice->teknisi->id;



					$teknisiTugas[] = $tektugservice->teknisi->name;

				}

			}

		}











		$data = array(



			'service' => $service,



			'tgl_pengerjaan' =>	$tgl_pengerjaan,



			'kode' => $kode,



			'projects' => $jenis_projects,



			'barang' => $barang,



			'keluhan' => $keluhan,



			'perusahaan' => $perusahaan,



			'teknisi' => $teknisiTugas,



			'penjualan' => $penjualan,



			'alasan' => $alasan,



			'alasancustomer' => $alasancustomer,



			'alasan1' => $alasankondisi1,



			'alasan2' => $alasankondisi2,



			'sparepart' => $sparepart



		);



		echo json_encode($data);

	}







	//API Get Sync Invoice Pengerjaan Service



	public function service_pengerjaan_sync_get()



	{







		$id = $this->input->get('id');







		$service 				= ServiceModel::find($id);







		$orderpenjualan 		= PenawaranbeeModel::where('id_service', $service->id)->desc()->first();



		// dd($orderpenjualan);



		if (!$service) {







			echo goResult(false, 'Service not found');







			return;

		}



		if ($orderpenjualan) {







			echo goResult(false, 'Invoice is already');







			return;

		}







		$countbiaya 			= $service->nominalbiaya_service + $service->biaya_akomodasi;







		if ($countbiaya > 0) {







			$id_customerbee 	= '';







			$customerbee 		= CustomernewModel::find($service->id_customernew);







			$mitraBisnisUD 		= ApiBee::getMitrabisnisUD();















			foreach ($mitraBisnisUD['data'] as $key => $value) {







				if ($value->code == $customerbee->code) {







					$id_customerbee = $id_customerbee . '' . $value->id;

				}

			}















			if ($service->id_cabang == 1) {







				$branch 		= "1";







				$gudang 		= "1";

			} else {







				$branch 		= "2";







				$gudang 		= "5";

			}















			$codeorderpenjualan 	= PenawaranbeeModel::where('name_db', 'UD')->desc()->first();







			if (!$codeorderpenjualan) {







				$isToday 			= explode('-', date('Y-m-d'));







				$isYear 			= $isToday[0];







				$year 				= substr($isYear, -2);







				$month 				= $isToday[1];







				$day 				= $isToday[2];







				$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

			} else {















				$today 				= explode(' ', $codeorderpenjualan->created_at);







				$dateToday 			= substr($today[0], 0, -3);















				if ($dateToday == date('Y-m')) {















					$year 			= substr(date('Y'), -2);







					$codepenawaran 	= explode('-', $codeorderpenjualan->no_transaksi);







					$sumpenawaran 	= (int) $codepenawaran[2];















					$newcode 			= $sumpenawaran + 1;







					if ($newcode > 0 && $newcode <= 9) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-00' . $newcode;

					} elseif ($newcode > 9 && $newcode <= 99) {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-0' . $newcode;

					} else {







						$newcodeorder 	= 'SRV-' . $year . '' . date('m') . '-' . $newcode;

					}

				} else {







					$isToday 			= explode('-', date('Y-m-d'));







					$isYear 			= $isToday[0];







					$year 				= substr($isYear, -2);







					$month 				= $isToday[1];







					$day 				= $isToday[2];







					$newcodeorder 		= 'SRV-' . $year . '' . $month . '-001';

				}

			}















			$sumakomodasi 		= $service->biaya_akomodasi;







			$sumservice 		= $service->nominalbiaya_service;







			$totalprice 		= $countbiaya;







			$product            = array();







			$product[]          = array(







				"baseprice"             => $sumakomodasi,







				"basesubtotal"          => $sumakomodasi * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 1,







				"item_id"               => "1645",







				"itemname"              => "Biaya Akomodasi",







				"listprice"             => $sumakomodasi,







				"qty"                   => 1,







				"subtotal"              => $sumakomodasi * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => '4742',







				"wh_id"                 => $gudang







			);















			$product[]          = array(







				"baseprice"             => $sumservice,







				"basesubtotal"          => $sumservice * 1,







				"conv"                  => "1",







				"discamt"               => "0",







				"discexp"               => "0",







				"dno"                   => 2,







				"item_id"               => "1644",







				"itemname"              => "Biaya Service",







				"listprice"             => $sumservice,







				"qty"                   => 1,







				"subtotal"              => $sumservice * 1,







				"totaldiscamt"          => "0",







				"calcmtd2"              => '',







				"calcmtd3"              => '',







				"calcmtd4"              => '',







				"dnote"                 => '',







				"unit"                  => "4739",







				"wh_id"                 => $gudang







			);















			$mainproduct        = array();







			$mainproduct[]      = array(







				"branch_id" => $branch,







				"bp_id"     => $id_customerbee,







				"crc_id"    => "1",







				"discexp"   => "0",







				"sods"      => $product,







				"srep_id"   => "7",







				"taxed"     => "0",







				"subtotal"  => $totalprice,







				"total"     => $totalprice,







				"trxdate"   => date('Y-m-d'),







				"trxno"     => $newcodeorder







			);















			$dataInsert         = array(







				"soarray"       => $mainproduct







			);















			$newpenawaran 		= ApiBee::postPenawaranUD($dataInsert);







			if ($newpenawaran['status'] != 200) {







				echo goResult(false, $newpenawaran['msg']);







				return;

			}















			$penawaranbee 					= new PenawaranbeeModel;







			$penawaranbee->tgl_transaksi 	= date('Y-m-d');







			$penawaranbee->name_db 			= 'UD';







			$penawaranbee->no_transaksi 	= $newcodeorder;







			$penawaranbee->id_sales 		= 7;







			$penawaranbee->id_teknisi 		= 43;







			$penawaranbee->id_cabang 		= $branch;







			$penawaranbee->id_service 		= $service->id;







			$penawaranbee->id_mitrabisnis 	= $id_customerbee;







			$penawaranbee->code_mitrabisnis = $customerbee->code;







			$penawaranbee->subtotal 		= $totalprice;















			if ($penawaranbee->save()) {







				$penawaranbeedetail 					= new PenawaranbeedetailModel;







				$penawaranbeedetail->id_penawaranbee 	= $penawaranbee->id;







				$penawaranbeedetail->id_gudang 			= $gudang;







				$penawaranbeedetail->id_item 			= '1645';







				$penawaranbeedetail->code_item 			= '001082';







				$penawaranbeedetail->name_item 			= 'Biaya Akomodasi';







				$penawaranbeedetail->price 				= $service->biaya_akomodasi;







				$penawaranbeedetail->qty 				= 1;







				$penawaranbeedetail->subtotal 			= $service->biaya_akomodasi;







				$penawaranbeedetail->ppn 				= 0;







				$penawaranbeedetail->save();















				$penawaranbeedetail2 					= new PenawaranbeedetailModel;







				$penawaranbeedetail2->id_penawaranbee 	= $penawaranbee->id;







				$penawaranbeedetail2->id_gudang 		= $gudang;







				$penawaranbeedetail2->id_item 			= '1644';







				$penawaranbeedetail2->code_item 		= '001081';







				$penawaranbeedetail2->name_item 		= 'Biaya Service';







				$penawaranbeedetail2->price 			= $service->nominalbiaya_service;







				$penawaranbeedetail2->qty 				= 1;







				$penawaranbeedetail2->subtotal 			= $service->nominalbiaya_service;







				$penawaranbeedetail2->ppn 				= 0;







				$penawaranbeedetail2->save();















				echo goResult(true, 'Sync invoice success');







				return;

			} else {







				echo goResult(true, 'Sync invoice success');







				return;

			}

		} else {







			echo goResult(false, 'Price cannot zero');







			return;

		}

	}







	//API SendQiscus Pancake Pengerjaan Service



	public function service_pengerjaan_sendqiscus_get()



	{



		$teknisi 			= TeknisiModel::find($this->session->userdata('teknisi_id'));



		if (empty($teknisi)) {







			$this->response([



				'success' => false,



				'message' => 'Anda Belum Login'



			], 401);



			return;

		}



		$this->data['teknisi'] = $teknisi;







		$StatusTeknisi 			= $this->data['teknisi']->status;



		$NameTeknisi = $this->data['teknisi']->username;







		$query 			= $this->db->select('*')->from('teknisi')->where('username', $NameTeknisi)->get();



		$teknisitugas = $query->row();



		$id = $this->input->get('id');







		$service 				= ServiceModel::find($id);







		$spk 					= SpkModel::where('id_service', $service->id)->where('id_teknisi', $teknisitugas->id)->where('status_sementara', 1)->desc()->first();



		if ($spk) {







			$solusi 			= $spk->solusi;

		} else {







			$solusi 			= '-';

		}















		if ($service->id_projects == 2) {







			$codeprojects 	= 'I' . $service->kode;

		} else {







			$codeprojects 	= 'S' . $service->kode;

		}







		if ($service->status == '0') {







			$namestatus 	= 'Pelaporan';

		} elseif ($service->status == '1') {







			$namestatus 	= 'Pengerjaan';

		} elseif ($service->status == '2') {







			$namestatus 	= 'Menunggu Konfirmasi';

		} elseif ($service->status == '3') {







			$namestatus 	= 'Selesai';

		} else {







			$namestatus 	= 'Pending';

		}





















