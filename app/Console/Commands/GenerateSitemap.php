<?php

namespace App\Console\Commands;

use App\Blog;
use App\Brand;
use App\Category;
use App\FlashDeal;
use App\Page;
use App\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function createModelSiteMap($data, $xml_file_name, $s_url)
    {
        $contentSitemap_ar = Sitemap::create();
        $contentSitemap_en = Sitemap::create();
        foreach ($data as $one) {

            // Write the public url of this content which is in our case localhost/content/{id}
            $url_ar = url('/eg/' . $s_url . $one->slug_ar);
            $url_en = url('/en/' . $s_url . $one->slug_en);
            // Add the url to the sitemap
            $contentSitemap_ar->add($url_ar);
            $contentSitemap_en->add($url_en);
        }
        $contentSitemap_ar->writeToFile(public_path('/sitemap/' . $xml_file_name . '_sitemap_ar.xml'));
        $contentSitemap_en->writeToFile(public_path('/sitemap/' . $xml_file_name . '_sitemap_en.xml'));
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Pages
        $pages = Page::select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($pages, 'pages', '');
        //blogs
        $blogs = Blog::select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($blogs, 'blogs', 'blog/');
        //admin Products
        $admin_products = Product::where(['added_by' => 'admin', 'published' => 1])->select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($admin_products, 'admin_products', 'product/');
        //seller Products
        $seller_products = Product::where(['added_by' => 'seller', 'published' => 1])->select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($seller_products, 'seller_products', 'product/');
        //categories
        $categories = Category::where(['published' => 1])->select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($categories, 'categories', 'category/');
        //brands
        $brands = Brand::select('slug_ar', 'slug_en')->orderBy('created_at', 'desc')->get();
        $this->createModelSiteMap($brands, 'brands', 'brand/');
        //flash_deals 
        $flash_deals = FlashDeal::where('status', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->select('slug_ar', 'slug_en')->where('end_date', '>=', strtotime(date('d-m-Y')))->orderBy('created_at', 'desc')->get();
        // $flash_deals->where('start_date', '<=', strtotime(date('d-m-Y')));
        // $flash_deals->where('end_date', '>=', strtotime(date('d-m-Y')));
        $this->createModelSiteMap($flash_deals, 'flash_deals', 'flash-deal/');

        SitemapIndex::create()
            ->add('sitemap/sitemap_index_ar.xml')
            ->add('sitemap/sitemap_index_en.xml')
            ->writeToFile(public_path('sitemap/sitemap_index.xml'));
        SitemapIndex::create()
            ->add('sitemap/pages_sitemap_ar.xml')
            ->add('sitemap/blogs_sitemap_ar.xml')
            ->add('sitemap/admin_products_sitemap_ar.xml')
            ->add('sitemap/seller_products_sitemap_ar.xml')
            ->add('sitemap/categories_sitemap_ar.xml')
            ->add('sitemap/brands_sitemap_ar.xml')
            ->add('sitemap/flash_deals_sitemap_ar.xml')
            ->writeToFile(public_path('sitemap/sitemap_index_ar.xml'));

        SitemapIndex::create()
            ->add('sitemap/pages_sitemap_en.xml')
            ->add('sitemap/blogs_sitemap_en.xml')
            ->add('sitemap/admin_products_sitemap_en.xml')
            ->add('sitemap/seller_products_sitemap_en.xml')
            ->add('sitemap/categories_sitemap_en.xml')
            ->add('sitemap/brands_sitemap_en.xml')
            ->add('sitemap/flash_deals_sitemap_en.xml')
            ->writeToFile(public_path('sitemap/sitemap_index_en.xml'));
        // SitemapGenerator::create(config('app.url'))
        //     ->writeToFile(public_path('sitemap/all_sitemap.xml'));
    }
}
