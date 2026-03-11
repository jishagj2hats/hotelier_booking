<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Controller;

use Hotelier\HotelierBooking\Domain\Repository\OfferRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class OfferController extends ActionController
{
    protected OfferRepository $offerRepository;

    public function injectOfferRepository(OfferRepository $offerRepository): void
    {
        $this->offerRepository = $offerRepository;
    }

    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        //  Landing page — active + upcoming, hide expired only
        $offers = $this->offerRepository->findNonExpiredOffers();
    
        $grouped = [
            'all'      => [],
            'flash'    => [],
            'weekend'  => [],
            'seasonal' => [],
            'general'  => [],
        ];
    
        foreach ($offers as $offer) {
            $grouped['all'][] = $offer;
            $type = $offer->getOfferType();
            if (isset($grouped[$type])) {
                $grouped[$type][] = $offer;
            }
        }
    
        $this->view->assignMultiple([
            'offers'        => $offers,
            'groupedOffers' => $grouped,
            'site'          => $this->request->getAttribute('site'),
            'now'           => time(),
        ]);
    
        return $this->htmlResponse();
    }
}

